<?php

namespace lazymanso\kuaidi100;

use lazymanso\kuaidi100\config\Command;

class Logistics extends Common
{
	/**
	 * 授权key
	 * @var string
	 */
	protected $strKey = '';

	/**
	 * 设置接口返回的数据格式
	 * @var string
	 */
	protected $strSchema = 'json';

//	/**
//	 * 根据运单号判断所属物流公司
//	 * @var int
//	 */
//	protected $nAutoCom = 0;
//
//	/**
//	 * 是否开启国际版本
//	 * @var int
//	 */
//	protected $nInterCom = 0;

	public function __construct(array $aConfig)
	{
		$this->strKey = $aConfig['key'];
		$this->setSchema($aConfig['schema']);
	}

	public function setSchema($strSchema = '')
	{
		if (empty($strSchema))
		{
			$this->strSchema = 'json';
		}
		else
		{
			$strSchema = strtolower($strSchema);
			if (in_array($strSchema, ['json', 'xml']))
			{
				$this->strSchema = $strSchema;
			}
		}
	}

	/**
	 * 订阅接口
	 * @param array $aInput [in]参数列表
	 * <pre>
	 * company - string,必填,订阅的快递公司的编码，一律用小写字母
	 * number - string,必填,订阅的快递单号，单号的最大长度是32个字符
	 * from - string,选填,出发地城市，省-市-区
	 * to - string,选填,目的地城市，省-市-区
	 * parameters - array,必填,其他参数集
	 * parameters.callbackurl - string,必填,回调地址
	 * </pre>
	 * @return boolean|array 返回 false 时表示出错,返回空数组时表示订单不存在
	 */
	public function subscribe(array $aInput)
	{
		if (!$this->checkFields($aInput, ['company', 'number', 'parameters'], [], true))
		{
			return false;
		}
		elseif (!$this->checkFields($aInput['parameters'], ['callbackurl'], [], true))
		{
			return false;
		}
		$aInput['key'] = $this->strKey;
		// 请求参数
		$aRequest = [
			'schema' => $this->strSchema,
			'param' => $aInput,
		];
		if (false === $aResponse = $this->doCommand(Command::SUBSCRIBE, $aRequest))
		{
			return false;
		}
		return $aResponse;
	}
}
