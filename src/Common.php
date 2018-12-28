<?php

namespace lazymanso\kuaidi100;

use lazymanso\common\Base;
use lazymanso\kuaidi100\config\Command;
use lazymanso\common\Curl;

/**
 * Description of Common
 *
 * @author Administrator
 */
class Common extends Base
{
	/**
	 * php curl
	 * @var \lazymanso\common\Curl
	 */
	protected $oCurlUtil;

	/**
	 * 向快递100接口发送请求
	 * @param int $nCommand [in]api接口代码
	 * @param mixed $aSendData [in]请求参数
	 * @return mxied 经过处理的微信接口响应内容，返回 false 时表示出错
	 */
	protected function doCommand($nCommand, array $aSendData)
	{
		if (!$aRequest = Command::get($nCommand))
		{
			$this->setError('获取快递100接口请求设置信息失败');
			return false;
		}
		// 发送请求
		$this->oCurlUtil = new Curl;
		$result = '';
		array_walk_recursive($aSendData, function(&$value, $key) {
			if (!in_array($key, ['callbackurl']))
			{
				$value = urlencode($value);
			}
		});
		$aSendData['param'] = json_encode($aSendData['param']);
		// http query string
		$postParam = http_build_query($aSendData);
		$cmdResult = $this->oCurlUtil->post($aRequest['url'], $postParam, $result);
		$strTraceParam = is_array($aSendData) ? print_r($aSendData, true) : $aSendData;
		if (!$cmdResult)
		{
			$this->setError('请求快递100接口失败 ' . $aRequest['method'] . ' ' . $aRequest['url'] . ' ' . $strTraceParam);
			return false;
		}
		$response = $this->checkResult($result);
		if (false === $response)
		{
			$this->setError($this->getError() . '，接口信息：' . print_r($aRequest, true) . '，接口参数：' . $strTraceParam);
			return false;
		}
		return $response;
	}

	/**
	 * 检查并处理curl返回结果
	 * @param mixed $result [in]curl返回数据
	 * @return mixed 返回 false ,表示有错误
	 */
	protected function checkResult($result = '')
	{
		if (0 === strpos($result, '<xml>'))
		{
			$aResult = $this->xml2array($result);
		}
		elseif (false !== strpos($_SERVER['CONTENT_TYPE'], 'application/json') || 0 === strpos($result, '{"'))
		{
			$aResult = (array) json_decode($result, true);
		}
		else
		{
			return $result;
		}
		if (!$aResult['result'])
		{
			$this->setErrorNo($aResult['returnCode']);
			$this->setError($aResult['message']);
			return false;
		}
		return $aResult;
	}
}
