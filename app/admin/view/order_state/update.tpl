{extend name="../../common/view/base/form" /}

{block name="title"}订单状态{/block}
{block name="head"}<script type="text/javascript" src="public/base/jquery.colorpicker.js"></script>
<script type="text/javascript" src="public/admin/js/{:request()->controller()}.js"></script>{/block}

{block name="nav"}
{if condition="$Admin['level']==1||in_array($Permission[request()->controller()]['index'],$AdminPermit)"}<li><a href="{:url('/'.parse_name(request()->controller()).'/index')}">列表</a></li>{/if}
{if condition="$Admin['level']==1||in_array($Permission[request()->controller()]['add'],$AdminPermit)"}<li><a href="{:url('/'.parse_name(request()->controller()).'/add')}">添加</a></li>{/if}
<li class="current"><a href="{:url('/'.parse_name(request()->controller()).'/update',['id'=>input('get.id')])}">修改</a></li>
{/block}

{block name="form"}
<form method="post" action="" class="form">
  <table>
    <tr><td>状态名称：</td><td><input type="text" name="name" value="{$One['name']}" class="input-text"></td></tr>
    <tr><td>状态颜色：</td><td><input type="text" name="color" value="{$One['color']}" class="input-text"></td><td>留空则为黑色，颜色值可以为：表示颜色的英文单词，如red；16进制的颜色代码，如#FF0000；10进制的颜色代码，如rgb(255,0,0)。</td></tr>
    <tr><td colspan="2" class="left"><input type="submit" value="确认修改" class="btn btn-primary radius"></td></tr>
  </td>
</form>
{/block}