<?php

Yii::import('bootstrap.widgets.TbExtendedGridView');

class NiExtendedGridView extends TbExtendedGridView
{


	/**
	 * Renders pageSize selector.
	 */
	public function renderPagerExt()
	{
		
		if(!$this->enablePagination)
			return;

		$pager=array();
		$class='CLinkPager';
		if(is_string($this->pager))
			$class=$this->pager;
		elseif(is_array($this->pager))
		{
			$pager=$this->pager;
			if(isset($pager['class']))
			{
				$class=$pager['class'];
				unset($pager['class']);
			}
		}
		$pager['pages']=$this->dataProvider->getPagination();

		if($pager['pages']->getPageCount()>1)
		{
			echo '<div class="'.$this->pagerCssClass.' custom-pag">';
			$this->widget($class,$pager);
			KHtml::pageSizeSelector($this->id);
			echo '</div>';
		}
		else
			$this->widget($class,$pager);

	}

}

?>