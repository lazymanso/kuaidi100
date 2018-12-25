<?php

namespace lazymanso\kuaidi100;

class Logistics
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
	 * from - string,必填,出发地城市，省-市-区
	 * to - string,必填,目的地城市，省-市-区
	 * </pre>
	 * @param array $aOutput [out]响应内容
	 * @link https://pay.weixin.qq.com/wiki/doc/api/wxa/wxa_api.php?chapter=9_2
	 * @return boolean|array 返回 false 时表示出错,返回空数组时表示订单不存在
	 */
	public function subscribe(array $aInput)
	{
		if (!$this->checkFields($aInput, ['company', 'number', 'from', 'to', 'parameters'], [], true))
		{
			return false;
		}
		elseif (!$this->checkFields())
		{

		}
		// 请求参数
		$aRequest = [
			'schema' => $this->strSchema,
			'param' => [
				'company' => '',
			],
		];
		// 签名
		$aParam['sign'] = $this->sign($aParam);
		//
		if (false === $aResponse = $this->doCommand(Command::PAY_QUERY_ORDER, $aParam, 'xml'))
		{
			// ORDERNOTEXIST - 订单不存在
			if ('ORDERNOTEXIST' === $this->getErrorCode())
			{
				return [];
			}
			return false;
		}
		return $aResponse;
	}

	/**
	 * 产生随机字符串，不长于32位
	 * @param int $length
	 * @return string
	 */
	protected function createNoncestr($length = 32)
	{
		$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$str = '';
		for ($i = 0; $i < $length; $i++)
		{
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
}
