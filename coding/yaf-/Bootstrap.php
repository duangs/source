<?php
/**
 * @name Bootstrap
 * @author sh-ad\zhao.chen
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract{

    public function _initConfig() {
		//把配置保存起来
		$arrConfig = Yaf_Application::app()->getConfig();
		Yaf_Registry::set('config', $arrConfig);
	}

	public function _initPlugin(Yaf_Dispatcher $dispatcher) {
		//注册一个插件
		$objSamplePlugin = new SamplePlugin();
		$dispatcher->registerPlugin($objSamplePlugin);
	}	

	public function _initRoute(Yaf_Dispatcher $dispatcher) {
		//在这里注册自己的路由协议,默认使用简单路由
	}

	public function _initComposer(Yaf_dispatcher $dispatcher) {
		Yaf_Loader::import(dirname(__FILE__) . '/vendor/autoload.php');
	}

	public function _initSessionPath(Yaf_dispatcher $dispatcher) {
		//初始化Session，并启动
		$config = Yaf_Registry::get('config');

		$domain = $config['site']['domain'];
		$redis = $config['redis'];

		ini_set('session.save_handler','redis');
		ini_set('session.save_path','tcp://'.$redis['host'].':'.$redis['port']);
		ini_set('session.cookie_domain',$domain);
		ini_set('session.gc_maxlifetime','7200');

		Yaf_Session::getInstance()->start();

	}

	public function _initParams(Yaf_Dispatcher $dispatcher) {
		//初始化网站参数
		$config = Yaf_Registry::get('config');
		$request = $dispatcher->getRequest();
		$lang = $request->getQuery('lang');
		$skin = $request->getQuery('skin');
		$theme = $request->getQuery('theme');

		Cookie::init([
				'path'=>'/',
				'domain'=>$config['site']['domain'],
				'prefix'=>$config['site']['cookie']['prefix']
		]);

		if($config['site']['force']['lang']){
			$lang = $config['site']['force']['lang'];
		}else{
			$lang = Cookie::get($config['site']['cookie']['lang']);
			$lang = $lang !== false ? $lang : $config['site']['default']['lang'];
		}

		if($config['site']['force']['skin']){
			$skin = $config['site']['force']['skin'];
		}else{
			$skin = Cookie::get($config['site']['cookie']['skin']);
			$skin = $skin !== false ? $skin : $config['site']['default']['skin'];
		}


		if($config['site']['force']['theme']){
			$theme = $config['site']['force']['theme'];
		}else{
			$theme = Cookie::get($config['site']['cookie']['theme']);
			$theme = $theme !== false ? $theme : $config['site']['default']['theme'];
		}

		$runtime = array();

		$runtime['lang'] = $lang;
		$runtime['skin'] = $skin;
		$runtime['theme'] = $theme;

		Cookie::set($config['site']['cookie']['lang'], $lang, intval($config['site']['cookie']['expire']));
		Cookie::set($config['site']['cookie']['skin'], $skin, intval($config['site']['cookie']['expire']));
		Cookie::set($config['site']['cookie']['theme'], $theme, intval($config['site']['cookie']['expire']));

		// $session = Yaf_Session::getInstance();
		// $session->set($config['site']['cookie']['lang'], $lang);
		// $session->set($config['site']['cookie']['skin'], $skin);
		// $session->set($config['site']['cookie']['theme'], $theme);
		$www_url = 
		$assets_url = 

				
		Yaf_Registry::set('runtime', $runtime);
	}

	public function _initView(Yaf_Dispatcher $dispatcher){
		$www_url = Yaf_Registry::get('config')->get('site.www_url');
		$assets_url = Yaf_Registry::get('config')->get('site.assets_url') . '/' . Yaf_Registry::get('runtime')['theme'];
		$account_url = Yaf_Registry::get('config')->get('site.account_url');
		//在这里注册自己的view控制器，例如smarty,firekylin
		$smarty = new SmartyAdapter(null, Yaf_Registry::get('config')->get('smarty'));
		$dispatcher->setView($smarty);
		$view = $dispatcher->initView(Yaf_Registry::get('config')->get('smarty.template_dir'));
		$view->assign('www_url', $www_url);
		$view->assign('assets_url', $assets_url);
		$view->assign('account_url', $account_url);
		
	}
}
