<?php

namespace lazymanso\kuaidi100\config;

/**
 * 微信小程序接口命令到api url配置类
 */
class Command
{
	/** API基础资源地址 */
	const BASEAPIURI = 'https://poll.kuaidi100.com/'; //

	/* ====================================================================== */
	// 命令集合
	/** 订阅subscribe */
	const SUBSCRIBE = 2001000; //

	/**
	 * 数据对象字段的类型显示映射
	 * @static
	 * @access private
	 * @var array
	 */
	private static $_aMap = [
		self::SUBSCRIBE => ['post', self::BASEAPIURI . 'poll'],
	];

	/**
	 * 获取接口地址
	 * @access public
	 * @param int $nCode [in]代码
	 * @return mixed
	 */
	public static function get($nCode)
	{
		if (empty($nCode) || !isset(self::$_aMap[$nCode]))
		{
			return false;
		}
		$aConfig = self::$_aMap[$nCode];
		$method = $aConfig[0];
		$url = $aConfig[1];
		return [
			'method' => $method,
			'url' => $url,
		];
	}
}
