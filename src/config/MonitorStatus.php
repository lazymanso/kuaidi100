<?php

namespace lazymanso\kuaidi100\config;

/**
 * 物流监控状态
 */
class MonitorStatus
{
	/** 监控中 */
	const MONITOR_STATUS_POLLING = 'polling'; //
	/** 关闭，说明已经签收了 */
	const MONITOR_STATUS_SHUTDOWN = 'shutdown'; //
	/** 中止，某些因素导致的异常，物流无更新 */
	const MONITOR_STATUS_ABORT = 'abort'; //
	/** 重新推送 */
	const MONITOR_STATUS_UPDATEALL = 'updateall'; //

	/**
	 * 物流监控状态码说明映射
	 * @var array
	 */
	private static $_aMonitorStatusMapping = [
		self::MONITOR_STATUS_POLLING => '监控中',
		self::MONITOR_STATUS_SHUTDOWN => '关闭',
		self::MONITOR_STATUS_ABORT => '中止',
		self::MONITOR_STATUS_UPDATEALL => '重新推送',
	];

	/**
	 * 获取物流监控状态码说明
	 * @param string $strCode [in]状态码
	 * @return string|array
	 */
	public static function getMonitorStatus($strCode = '')
	{
		if (empty($strCode))
		{
			return self::$_aMonitorStatusMapping;
		}
		else
		{
			return isset(self::$_aMonitorStatusMapping[$strCode]) ? self::$_aMonitorStatusMapping[$strCode] : '未知监控状态';
		}
	}
}
