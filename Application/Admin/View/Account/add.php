<script>
<?php
//print_r($_SERVER['HTTP_HOST']);exit;
$aryRadios = array(
               1 => '明文模式',
               2 => '兼容模式',
               3 => '安全模式',
           );
echo 'var encryptType = '.json_encode($aryRadios).';';
?>
</script>
<!-- SELECT2 -->
<link rel="stylesheet" type="text/css" href="__PUBLIC__/adm/js/select2/select2.min.css" />
<!-- UNIFORM -->
<link rel="stylesheet" type="text/css" href="__PUBLIC__/adm/js/uniform/css/uniform.default.min.css" />
<!-- WIZARD -->
<link rel="stylesheet" type="text/css" href="__PUBLIC__/adm/js/bootstrap-wizard/wizard.css" />
<!-- UNIFORM -->
<script type="text/javascript" src="__PUBLIC__/adm/js/uniform/jquery.uniform.min.js"></script>
<!-- WIZARD -->
<script type="text/javascript" src="__PUBLIC__/adm/js/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/adm/js/select2/select2.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/adm/js/wxaccount.js"></script>

<div class="row">
        <div class="col-sm-12">
                <div class="page-header">
                        <include file="Common/page_header"/>
                        <div class="description">
                            绑定公众号后可以进行内容发布，回复管理和自定义菜单设置等一系列公众号接口操作！
                        </div>
                </div>
        </div>
</div>
<!-- /PAGE HEADER -->
<!-- SAMPLE -->
<div class="row">
    <div class="col-md-12">
        <!-- BOX -->
        <div class="box border blue" id="formWizard">
            <div class="box-title">
                    <h4><i class="fa fa-bars"></i><?php echo $aryDaohang['sub'][ACTION_NAME]['title'];?></h4>
            </div>
            <div class="box-body form">
                <form id="wizForm" action="#" class="form-horizontal" >
                <div class="wizard-form">
                   <div class="wizard-content">
                          <ul class="nav nav-pills nav-justified steps">
                                 <li>
                                        <a href="#account" data-toggle="tab" class="wiz-step">
                                        <span class="step-number">1</span>
                                        <span class="step-name"><i class="fa fa-check"></i> 微信开发者ID绑定 </span>   
                                        </a>
                                 </li>

                                 <li>
                                        <a href="#payment" data-toggle="tab" class="wiz-step active">
                                        <span class="step-number">2</span>
                                        <span class="step-name"><i class="fa fa-check"></i> 微信服务器配置</span>   
                                        </a>
                                 </li>
                                 <li>
                                        <a href="#confirm" data-toggle="tab" class="wiz-step">
                                        <span class="step-number">3</span>
                                        <span class="step-name"><i class="fa fa-check"></i> 提交保存 </span>   
                                        </a> 
                                 </li>
                          </ul>
                          <div id="bar" class="progress progress-striped progress-sm active" role="progressbar">
                                 <div class="progress-bar progress-bar-warning"></div>
                          </div>
                          <div class="tab-content">
                                 <div class="alert alert-danger display-none">
                                    <a class="close" aria-hidden="true" href="#" data-dismiss="alert">×</a>
                                    表单数据填写错误，请修正后继续。
                                 </div>
                                 <div class="alert alert-success display-none">
                                    <a class="close" aria-hidden="true" href="#" data-dismiss="alert">×</a>
                                    数据验证通过!
                                 </div>
                                 <div class="tab-pane active" id="account">
                                    <div class="form-group">
                                       <label class="control-label col-md-3"></label>
                                       <div class="col-md-6">
                                           <p><b>操作说明</b></p>
                                            <p>1. 进入官方微信公众号管理平台；<a href="https://mp.weixin.qq.com" target="_blank">https://mp.weixin.qq.com</a></p>
                                            <p>2. 进入"开发" > "基本配置"，点击"成为开发者"</p>
                                            <p>3. 将AppID、AppSecret填写到下面对应项中</p>
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="control-label col-md-3">公众号名称<span class="required">*</span></label>
                                       <div class="col-md-6">
                                              <input type="text" class="form-control" name="name" placeholder="请输入公众号名称"/>
                                              <span class="error-span"></span>
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="control-label col-md-3">AppID(应用ID)<span class="required">*</span></label>
                                       <div class="col-md-6">
                                              <input type="text" class="form-control" name="appid" placeholder="请输入应用ID"/>
                                              <span class="error-span"></span>
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="control-label col-md-3">AppSecret(应用密钥)<span class="required">*</span></label>
                                       <div class="col-md-6">
                                              <input type="text" class="form-control" name="appsecret" placeholder="请输入应用秘钥"/>
                                              <span class="error-span"></span>
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="control-label col-md-3">公众号描述</label>
                                       <div class="col-md-6">
                                              <input type="text" class="form-control" name="remark" placeholder="请输入公众号描述"/>
                                              <span class="error-span"></span>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="tab-pane" id="payment">
                                    <div class="form-group">
                                       <label class="control-label col-md-3"></label>
                                       <div class="col-md-6">
                                           <p><b>操作说明</b></p>
                                           <p>1. 复制下面的"URL(服务器地址)"内容</p>
                                           <p>2. 官方微信公众号管理平台（<a href="https://mp.weixin.qq.com" target="_blank">https://mp.weixin.qq.com</a>）中进入"开发" > "基本配置"，服务器配置一项点"修改配置"</p>
                                           <p>3. 将复制的内容粘贴到"URL"一项中，并按官方要求配置（Token、EncodingAESKey、消息加解密方式）其余项</p>
                                           <p>4. 将其余配置项分别复制到下面的对应项中，然后进入下一步</p>
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="control-label col-md-3">URL(服务器地址)<span class="required">*</span></label>
                                       <div class="col-md-6">
                                           <input type="text" class="form-control" onclick="this.select();" name="serviceurl" value="" readonly="readonly" />
                                           <label class="text-danger"><b>点击上面的地址鼠标右键复制到微信开发者设置中的URL(服务器地址)中</b></label>
                                           <span class="error-span"></span>
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="control-label col-md-3">Token<span class="required">*</span></label>
                                       <div class="col-md-6">
                                              <input type="text" placeholder="必须为英文或数字，长度为3-32字符" class="form-control" name="token"/>
                                              <span class="error-span"></span>
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="control-label col-md-3">EncodingAESKey<span class="required">*</span></label>
                                       <div class="col-md-6">
                                              <input type="text" placeholder="消息加密密钥由43位字符组成" class="form-control" name="aeskey"/>
                                              <span class="error-span"></span>
                                       </div>
                                    </div>												 
                                    <div class="form-group">
                                       <label class="control-label col-md-3">消息加解密方式<span class="required">*</span></label>
                                       <div class="col-md-8">
                                       <?php
                                       $encrypt = isset($encrypt) && isset($aryRadios[$encrypt])? $encrypt: 1;
                                       foreach ($aryRadios as $key=>$val) {
                                           $checked = $key == $encrypt? ' checked="checked"': '';
                                       ?>
                                           <label class="radio-inline">
                                                <input type="radio" name="encrypt" value="<?php echo $key;?>" data-title="<?php echo $aryRadios[$key];?>" class="uniform" <?php echo $checked;?> />
                                                <?php echo $aryRadios[$key];?>
                                           </label>
                                       <?php } ?>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="tab-pane" id="confirm">
                                        <h3 class="block">下列为将要保存的绑定信息，请确认相关项内容和官方平台中设置一样！</h3>
                                        <h4 class="form-section">微信公众号开发者基本信息</h4>
                                        <div class="well">
                                                <div class="form-group">
                                                   <label class="control-label col-md-3">公众号名称:</label>
                                                   <div class="col-md-6">
                                                       <p class="form-control-static" data-display="name"></p>
                                                   </div>
                                                </div>
                                                <div class="form-group">
                                                   <label class="control-label col-md-3">AppID(应用ID):</label>
                                                   <div class="col-md-6">
                                                       <p class="form-control-static" data-display="appid"></p>
                                                   </div>
                                                </div>
                                                <div class="form-group">
                                                   <label class="control-label col-md-3">AppSecret(应用密钥):</label>
                                                   <div class="col-md-6">
                                                       <p class="form-control-static" data-display="appsecret"></p>
                                                   </div>
                                                </div>
                                                <div class="form-group">
                                                   <label class="control-label col-md-3">公众号描述:</label>
                                                   <div class="col-md-6">
                                                          <p class="form-control-static" data-display="remark"></p>
                                                   </div>
                                                </div>
                                        </div>
                                        <h4 class="form-section">微信服务器配置</h4>
                                        <div class="well">														
                                                <div class="form-group">
                                                   <label class="control-label col-md-3">URL(服务器地址):</label>
                                                   <div class="col-md-6">
                                                       <p class="form-control-static" data-display="serviceurl"></p>
                                                   </div>
                                                </div>
                                                <div class="form-group">
                                                   <label class="control-label col-md-3">Token:</label>
                                                   <div class="col-md-6">
                                                       <p class="form-control-static" data-display="token"></p>
                                                   </div>
                                                </div>
                                                <div class="form-group">
                                                   <label class="control-label col-md-3">EncodingAESKey:</label>
                                                   <div class="col-md-6">
                                                       <p class="form-control-static" data-display="aeskey"></p>
                                                   </div>
                                                </div>
                                                <div class="form-group">
                                                   <label class="control-label col-md-3">消息加解密方式:</label>
                                                   <div class="col-md-6">
                                                       <p class="form-control-static" data-display="encrypt"></p>
                                                   </div>
                                                </div>
                                        </div>
                                 </div>
                          </div>
                   </div>
                   <div class="wizard-buttons">
                          <div class="row">
                                 <div class="col-md-12">
                                        <div class="col-md-offset-3 col-md-9">
                                           <a href="javascript:;" class="btn btn-default prevBtn">
                                                <i class="fa fa-arrow-circle-left"></i> 返回 
                                           </a>
                                           <a href="javascript:;" class="btn btn-primary nextBtn">
                                                下一步 <i class="fa fa-arrow-circle-right"></i>
                                           </a>
                                           <a href="javascript:;" class="btn btn-success submitBtn">
                                                保存 <i class="fa fa-arrow-circle-right"></i>
                                           </a>
                                        </div>
                                 </div>
                          </div>
                   </div>
                </div>
             </form>
            </div>
        </div>
        <!-- /BOX -->
    </div>
</div>
<!-- /FORMS -->
<div class="separator"></div>

<script type="text/javascript">
    $(function(){
        checkAdd();
    });
</script>