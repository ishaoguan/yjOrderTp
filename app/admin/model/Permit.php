<?php
namespace app\admin\model;

use Exception;
use think\Model;
use think\facade\Request;
use think\facade\Config;

class Permit extends Model{
	private $tableName = 'Permit';

	//查询总记录（主权限）
	public function total(){
		try {
			$total = $this->query("SELECT COUNT(*) count FROM `".Config::get('database.connections.mysql.prefix').strtolower($this->tableName)."` WHERE `parent_id`=0 AND (`name` LIKE '%".Request::get('keyword')."%' OR `controller` LIKE '%".Request::get('keyword')."%' OR `action` LIKE '%".Request::get('keyword')."%' OR `id` IN (SELECT `parent_id` FROM `".Config::get('database.connections.mysql.prefix').strtolower($this->tableName)."` WHERE `name` LIKE '%".Request::get('keyword')."%' OR `controller` LIKE '%".Request::get('keyword')."%' OR `action` LIKE '%".Request::get('keyword')."%'))");
			return $total[0]['count'];
		} catch (Exception $e){
			echo $e->getMessage();
			return [];
		}
	}
	
	//查询所有（主权限）
	public function all($firstRow){
		try {
			return $this->query("SELECT `id`,`name`,`controller`,`action`,`is_default`,`parent_id`,`sort` FROM `".Config::get('database.connections.mysql.prefix').strtolower($this->tableName)."` WHERE `parent_id`=0 AND (`name` LIKE '%".Request::get('keyword')."%' OR `controller` LIKE '%".Request::get('keyword')."%' OR `action` LIKE '%".Request::get('keyword')."%' OR `id` IN (SELECT `parent_id` FROM `".Config::get('database.connections.mysql.prefix').strtolower($this->tableName)."` WHERE `name` LIKE '%".Request::get('keyword')."%' OR `controller` LIKE '%".Request::get('keyword')."%' OR `action` LIKE '%".Request::get('keyword')."%')) ORDER BY `sort` ASC LIMIT $firstRow,".Config::get('app.page_size'));
		} catch (Exception $e){
			echo $e->getMessage();
			return [];
		}
	}
	
	//查询所有（主权限，不分页）
	public function all2($ids=''){
		try {
			$object = $this->field('id,name,controller,action')->order(['sort'=>'ASC'])->where(['parent_id'=>0]);
			return $ids ? $object->where('id','IN',$ids)->select()->toArray() : $object->select()->toArray();
		} catch (Exception $e){
			echo $e->getMessage();
			return [];
		}
	}
	
	//查询所有（子权限）
	public function all3($parent_id){
		try {
			return $this->field('id,name,controller,action,is_default,sort')
						->where('name|controller|action','LIKE','%'.Request::get('keyword').'%')
						->where(['parent_id'=>$parent_id])
						->order(['sort'=>'ASC'])
						->select()
						->toArray();
		} catch (Exception $e){
			echo $e->getMessage();
			return [];
		}
	}
	public function all5($ids,$parent_id){
		try {
			return $this->field('name')->where('id','IN',$ids)->where(['parent_id'=>$parent_id])->order(['sort'=>'ASC'])->select()->toArray();
		} catch (Exception $e){
			echo $e->getMessage();
			return [];
		}
	}
	
	//查询所有（默认权限）
	public function all4(){
		try {
			return $this->field('id')->where(['is_default'=>1])->order(['id'=>'ASC'])->select()->toArray();
		} catch (Exception $e){
			echo $e->getMessage();
			return [];
		}
	}
	
	//查询一条
	public function one($id=0){
		try {
			$map['id'] = $id ? $id : Request::get('id');
			return $this->field('name,parent_id,is_default')->where($map)->find();
		} catch (Exception $e){
			echo $e->getMessage();
			return [];
		}
	}
	
	//设置和取消默认
	public function isDefault($is_default){
		return $this->where(['id'=>Request::get('id')])->update(['is_default'=>$is_default]);
	}
	
	//排序
	public function sort($id,$sort){
		return $this->where(['id'=>$id])->update(['sort'=>$sort]);
	}
}