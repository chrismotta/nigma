<?php

/**
 * This is the model class for table "daily_publishers".
 *
 * The followings are the available columns in table 'daily_publishers':
 * @property integer $id
 * @property integer $placements_id
 * @property integer $country_id
 * @property string $imp
 * @property string $imp_adv
 * @property string $revenue
 * @property string $spend
 * @property string $profit
 * @property string $profit_percent
 * @property string $eCPM
 * @property string $comment
 * @property string $date
 *
 * The followings are the available model relations:
 * @property GeoLocation $country
 * @property Placements $placements
 */
class DailyPublishers extends CActiveRecord
{
	public $total;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'daily_publishers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('imp, revenue, spend, profit, date', 'required'),
			array('placements_id, country_id', 'numerical', 'integerOnly'=>true),
			array('imp, imp_adv, revenue, spend, profit, profit_percent, eCPM', 'length', 'max'=>11),
			array('comment', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, placements_id, country_id, imp, imp_adv, revenue, spend, profit, profit_percent, eCPM, comment, date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'country' => array(self::BELONGS_TO, 'GeoLocation', 'country_id'),
			'placements' => array(self::BELONGS_TO, 'Placements', 'placements_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'             => 'ID',
			'placements_id'  => 'Placements',
			'country_id'     => 'Country',
			'imp'            => 'Imp',
			'imp_adv'        => 'Imp Adv',
			'revenue'        => 'Revenue',
			'spend'          => 'Spend',
			'profit'         => 'Profit',
			'profit_percent' => 'Profit Percent',
			'eCPM'           => 'E Cpm',
			'comment'        => 'Comment',
			'date'           => 'Date',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('placements_id',$this->placements_id);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('imp',$this->imp,true);
		$criteria->compare('imp_adv',$this->imp_adv,true);
		$criteria->compare('revenue',$this->revenue,true);
		$criteria->compare('spend',$this->spend,true);
		$criteria->compare('profit',$this->profit,true);
		$criteria->compare('profit_percent',$this->profit_percent,true);
		$criteria->compare('eCPM',$this->eCPM,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DailyPublishers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTops($startDate=NULL, $endDate=NULL, $order)
	{
		if( !$startDate )	
			$startDate = 'today' ;
		if( !$endDate ) 
			$endDate = 'today';
		
		$startDate = date('Y-m-d', strtotime($startDate));
		$endDate   = date('Y-m-d', strtotime($endDate));

		$criteria            = new CDbCriteria;
		$criteria->condition = "date(t.date) BETWEEN '" . date('Y-m-d', strtotime($startDate)) . "' AND '" . date('Y-m-d', strtotime($endDate)) . "'";
		// $criteria->with = array('placements', 'placements.publishers');
		
		switch ($order) {
			case 'imp':
				$criteria->select    = array('t.placements_id','case SUM(imp_adv) when 0 then SUM(imp) else SUM(imp_adv) end  as total');
				$criteria->group     = "t.placements_id";
				break;
			case 'spend':
				$select="placements_id, ";
				$orderby = "sum(t.spend / 
						(
							SELECT (
									CASE (
										SELECT publishers.currency
										FROM publishers,placements
										WHERE publishers.id=placements.publishers_id
										AND t.placements_id=placements.id
									) WHEN 'USD' THEN (
										SELECT 1
									)"; 
				$currencyModel = new Currency;
				$currency      = $currencyModel->attributes;
				array_pop($currency); // remove id
				array_pop($currency); // remove date
				foreach ($currency as $key => $value) {
					$orderby .= " WHEN '" . $key . "' THEN ( SELECT " . $key . ")";
				}

				$orderby .= 		" END
									)
								FROM currency c 
								WHERE date(c.date)<=t.date
								ORDER BY c.date DESC
								LIMIT 1
							)
						)";
				$select           .=$orderby." as total";
				$criteria->select = $select;
				$criteria->group  = "t.placements_id";
				$criteria->order  = $orderby." DESC";
				break;

			case 'profit':
				$criteria->select = array(
							'placements_id', 
							'sum(profit) as total'
							);
				$criteria->order='SUM(profit) DESC';
				$criteria->group='placements_id';
				break;
			
			default:
				# code...
				break;
		}

		$criteria->limit    = 6;
		$r                  = DailyPublishers::model()->findAll( $criteria );
		foreach ($r as $value) {
			$totals[]        = doubleval($value->total);	
			$placements_id[] = $value->placements_id;		
		}
		
		$result=array(
			'totals' => $totals, 
			'ids'    => $placements_id,
			'types'  => 'publisher'
			);

		$return['array']        = $result;
		$return['dataProvider'] = new CActiveDataProvider($this, array(
			'criteria'   =>$criteria,
			'pagination' =>false,
		));
		return $return;
	}

	public function getTotals($startDate=null, $endDate=null,$accountManager=NULL) {
			
		if(!$startDate)	$startDate = 'today' ;
		if(!$endDate) $endDate     = 'today';
		$startDate                 = date('Y-m-d', strtotime($startDate));
		$endDate                   = date('Y-m-d', strtotime($endDate));
		$dataTops                  =array();
		$spends                    =array();
		$revenues                  =array();
		$profits                   =array();
		$impressions               =array();
		$dates                     =array();

		foreach (Utilities::dateRange($startDate,$endDate) as $date) {
			$dataTops[$date]['spends']      =0;
			$dataTops[$date]['revenues']    =0;
			$dataTops[$date]['profits']     =0;
			$dataTops[$date]['impressions'] =0;
		}
		$criteria=new CDbCriteria;
		$criteria->condition = "date(t.date) BETWEEN '" . date('Y-m-d', strtotime($startDate)) . "' AND '" . date('Y-m-d', strtotime($endDate)) . "'";
		$criteria->with = array( 'placements', 'placements.publishers','placements.publishers.accountManager');
		
		if ( $accountManager != NULL) {
			if(is_array($accountManager))
			{
				$query="(";
				$i=0;
				foreach ($accountManager as $id) {	
					if($i==0)			
						$query.="accountManager.id=".$id;
					else
						$query.=" OR accountManager.id=".$id;
					$i++;
				}
				$query.=")";
				$criteria->addCondition($query);				
			}
			else
			{
				$criteria->compare('accountManager.id',$accountManager);
			}
		}

		$r         = self::model()->findAll( $criteria );
		foreach ($r as $value) {
			$dataTops[date('Y-m-d', strtotime($value->date))]['spends']      +=doubleval($value->getSpendUSD());	
			$dataTops[date('Y-m-d', strtotime($value->date))]['revenues']    +=doubleval($value->getRevenueUSD());
			$dataTops[date('Y-m-d', strtotime($value->date))]['profits']     +=doubleval($value->profit);
			$dataTops[date('Y-m-d', strtotime($value->date))]['impressions'] +=$value->getImp();
		}
		
		foreach ($dataTops as $date => $data) {
			$spends[]      =$data['spends'];
			$revenues[]    =$data['revenues'];
			$profits[]     =$data['profits'];
			$impressions[] =$data['impressions'];
			$dates[]       =$date;
		}
		$result=array(
			'spends'      => $spends, 
			'revenues'    => $revenues, 
			'profits'     => $profits, 
			'impressions' => $impressions, 
			'dates'       => $dates
			);
		
		return $result;
	}

	public function getSpendUSD()
	{
		$placement = Placements::model()->findByPk($this->placements_id);
		$publisher = Publishers::model()->findByPk($placement->publishers_id);
		$currency  = $publisher->currency;
	
		if ($currency == 'USD')	// if currency is USD dont apply type change
			return $this->revenue;

		$currencys = Currency::model()->findByDate($this->date);
		return $currency ? round($this->revenue / $currencys[$currency], 2) : 'Currency ERROR!';
	}

	public function getRevenueUSD()
	{
		$placement = Placements::model()->findByPk($this->placements_id);
		$exchange  = Exchanges::model()->findByPk($placement->exchanges_id);		
		$currency  = $exchange->currency;

		if ($currency == 'USD') // if currency is USD dont apply type change
			return $this->spend;

		$currencys = Currency::model()->findByDate($this->date);
		return $currencys ? round($this->spend / $currencys[$currency], 2) : 'Currency ERROR!';
	}

	public function getImp()
	{
		return $this->imp_adv ? intval($this->imp_adv) : intval($this->imp);
	}
}
