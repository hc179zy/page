<?php
namespace hcgrzh\page;
class Page{
	private static $totalPage=0;//总页数
	private static $totalNum=0;//总文章数
	private static $page=1;//默认当前也
	private static $pageSize=10;
	public 	static $rollNum=6;//分页栏每页显示的页数
	public 	static $pageSep='...';
	public  static $showPlate=[
		'page_head' =>true,
        'page_pre'  =>true,
		'page_num'	=>true,
        'page_nex'  =>true,
        'page_end'  =>true,
        'page_total'=>false,
	];
	public static $pageInfo=[
        'page_pre'  => false,
        'page_head' => false,
		'page_num'	=>[],
		'page_end'  => false,
        'page_nex'  => false,
        'page_total'=>false,
	];
	private static function setError($message=''):void {
		self::$message[]=$message;
	}	
	public static function getErrorArray():array{
		return self::$message;
	}	
	public static function getErrorString($role=','):string{
		return implode(',',self::$message);
	}
	//初始化
	public static function init($totalNum,$page=1,$pageSize=10):bool{
		if($pageSize==0){
			self::setError('pageSize 不能未0');
			return false;
		}
		self::$totalNum=$totalNum;
		self::$pageSize=$pageSize;
		self::$totalPage=ceil($totalNum/$pageSize);
		self::$page=$page;
		self::getPageData();
		return true;
	}
	//得到当前页
	public static function getPage():int{
		return self::$page;
	}
	//得到总页数
	public static function getTotalPage():int{
		return self::$totalPage;
	}
	//得到总条数
	public static function getTotalNum():int{
		return self::$totalNum;
	}
	public static function getPagePre():int{
		if(self::$page>1){return self::$page-1;}else{return 1;}
	}
	public static function getPageNext():int{
		if(self::$totalPage>self::$page){return self::$page+1;}else{return self::$page;}
	}
	//得到中间数据
	public static function getPageData():void{
		$now_roll_page=self::$rollNum/2;
		$now_roll_page_ceil=ceil($now_roll_page);
		//上一页
		if(self::$page>1 && self::$totalPage>self::$rollNum && self::$showPlate['page_pre']===true){
			self::$pageInfo['page_pre']=intval(self::$page-1);
		}
		//首页
		if(self::$totalPage>self::$rollNum && (self::$page-$now_roll_page)>=1){
			if(self::$showPlate['page_head']===true){
				self::$pageInfo['page_head']=intval(1);
			}
			if(self::$showPlate['page_num']===true){
				self::$pageInfo['page_num'][]=self::$pageSep;
			}
		}
		if(self::$showPlate['page_num']===true){
			//页码
			for($i=1;$i<=self::$rollNum;$i++){
				if((self::$page-$now_roll_page)<=0){
					$page=$i;
				}elseif((self::$page+$now_roll_page-1)>=self::$totalPage){
					$page=self::$totalPage-self::$rollNum+$i;
				}else{
					$page=self::$page-$now_roll_page_ceil+$i;
				}
				if($page>0 && $page!=self::$page){
					if($page<=self::$totalPage){
						self::$pageInfo['page_num'][]=$page;
					}else{
						break;
					}	
				}else{
					if($page>0 && self::$totalPage>1){
						self::$pageInfo['page_num'][]=$page;
					}
				}
				
			}
		}
		//尾页
		if(self::$totalPage>self::$rollNum && (self::$page+$now_roll_page)<self::$totalPage){
			if(self::$showPlate['page_num']===true){
				self::$pageInfo['page_num'][]=self::$pageSep;
			}
			if(self::$showPlate['page_end']===true){
				self::$pageInfo['page_end']=intval(self::$totalPage);
			}
		}
		//下一页
		if(self::$page<self::$totalPage && self::$totalPage>self::$rollNum){
			if(self::$showPlate['page_nex']===true){
				self::$pageInfo['page_nex']=self::$page+1;
			}
		}
		if(self::$showPlate['page_total']===true){
			self::$pageInfo['page_nex']=self::$totalPage;
		}
	}	
}
?>
