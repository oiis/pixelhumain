<?php 
  $this->renderPartial('webroot.themes.'.Yii::app()->theme->name.'.views.layouts.mail.header');
	$type="";
	if (@$parentType){
		if($parentType == "organizations"){
			$type="organization";
		}
		else if ($parentType == "projects"){
			$type="project";
		} 
		else if ($parentType == "events"){
			$type="event";
		}
	} 
	if ($type == "") {
		error_log("Unkown type when sending a mail askToBecomeAdimin");
		$type = "Unknwon";
	}

	$subtitle = yii::t("email","Demand to")." ";
	if (@$typeOfDemand){
		if($typeOfDemand == "admin"){
			$subtitle .= yii::t("email","administrate");
		}
		else if($typeOfDemand == "member"){
			$subtitle .= yii::t("email","join as member");
		}
		else if ($typeOfDemand == "contributor"){
			$subtitle .= yii::t("email","join as contributor");
		}
	}else{
		$subtitle .= yii::t("email","administrate");
		$typeOfDemand = "admin";
	}
  
  if($type==Project::COLLECTION)
    $dir="contributors";
  else if($type==Event::COLLECTION)
    $dir="attendees";
  else /*if($type==Organization::COLLECTION)*/
    $dir="members";

	$subtitle .= " ".yii::t("email","the ".$type);
	$url=Yii::app()->getRequest()->getBaseUrl(true)."/#page.type".$type.".id.".(String) $parent["_id"].".view.directory.dir.".$dir;
?>
<table class="row" style="border-spacing: 0;border-collapse: collapse;padding: 0;vertical-align: top;text-align: left;width: 100%;position: relative;display: table;"><tbody><tr style="padding: 0;vertical-align: top;text-align: left;"> <!-- Horizontal Digest Content -->
      <th class="small-12 large-12 columns first" style="color: #3c5665;font-family: Helvetica, Arial, sans-serif;font-weight: normal;padding: 0;margin: 0 auto;text-align: left;line-height: 19px;font-size: 15px;padding-left: 16px;padding-bottom: 16px;width: 564px;padding-right: 8px;">

              <h1 class="text-center" style="color: inherit;font-family: Helvetica, Arial, sans-serif;font-weight: normal;padding: 35px 0px 15px 0px;margin: 0;text-align: center;line-height: 1.3;word-wrap: normal;margin-bottom: 10px;font-size: 34px;"><?php echo $subtitle ?> <?php echo @$parent["name"]?></h1>
            <table style="border-spacing: 0;border-collapse: collapse;padding: 0;vertical-align: top;text-align: left;width: 100%;">
            <tr style="padding: 0;vertical-align: top;text-align: left;">
              <th style="color: #3c5665;font-family: Helvetica, Arial, sans-serif;font-weight: normal;padding: 0;margin: 0;text-align: left;line-height: 19px;font-size: 15px;">
                <!--http://localhost:8888/ph/images/betatest.png-->
              <a href="<?php echo Yii::app()->getRequest()->getBaseUrl(true) ?>" style="color: #e33551;font-family: Helvetica, Arial, sans-serif;font-weight: normal;padding: 0;margin: 0;text-align: left;line-height: 1.3;text-decoration: none;"><img align="right" width="200" src="<?php echo Yii::app()->getRequest()->getBaseUrl(true)."/images/bdb.png"?>" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;width: auto;max-width: 100%;clear: both;display: block;border: none;" alt="Intelligence collective"></a>
              <b>
              <h5 style="color: inherit;font-family: Helvetica, Arial, sans-serif;font-weight: normal;padding: 0;margin: 0;text-align: left;line-height: 1.3;word-wrap: normal;margin-bottom: 10px;font-size: 20px;"></h5></b><br>
              <b><?php echo yii::t("email","The user")." ".@$newPendingAdmin["username"]." ".Yii::t("email","asks to become")." ".yii::t("common",$typeOfDemand)." ".yii::t("email", "of")." ".@$parent["name"]?>.
              <br>
              <br><br>
               <?php echo yii::t("email", "For more details on the user")." ".@$newPendingAdmin["username"]?>, <?php echo yii::t("email","you can visit") ?> <a href="<?php echo Yii::app()->getRequest()->getBaseUrl(true)."/".$this->module->id."#person.detail.id.".(String) @$newPendingAdmin["_id"]."?tpl=directory2"?>"><?php echo yii::t("email","sa fiche profil")?></a>.
              <br>
              <br>
                <?php echo yii::t("email","In order to validate this user as")." ".yii::t("common",$typeOfDemand).", ".yii::t("email","go to the members' list of your")." "; ?>
           		<a href="<?php echo $url ?>"><?php echo yii::t("common",$type) ?></a>.
              <br>
              <br>
              <?php echo Yii::t("mail","If the link doesn&apos;t work, you can copy it in your browser&apos;s address"); ?> :
              <br><div style="word-break: break-all;"><?php echo $url?></div>
              <br>
              <br>
              <p style="margin: 0;margin-bottom: 10px;color: #777777 !important;font-family: Helvetica, Arial, sans-serif;font-weight: normal;padding: 0;text-align: left;line-height: 19px;font-size: 15px;"><?php echo Yii::t("mail","See you soon on") ?> <a href="<?php echo Yii::app()->getRequest()->getBaseUrl(true) ?>" style="color: #e33551;font-family: Helvetica, Arial, sans-serif;font-weight: normal;padding: 0;margin: 0;text-align: left;line-height: 1.3;text-decoration: none;"><?php echo Yii::app()->getRequest()->getBaseUrl(true) ?></a></p>
              <p style="margin: 0;margin-bottom: 10px;color: #777777 !important;font-family: Helvetica, Arial, sans-serif;font-weight: normal;padding: 0;text-align: left;line-height: 19px;font-size: 15px;"><?php echo Yii::t("mail","The team of communecter") ?></p>
            </th>
            </tr>
          </table>
        </th>
        </tr></tbody></table>
    <hr style="max-width: 580px;height: 0;border-right: 0;border-top: 0;border-bottom: 1px solid #cacaca;border-left: 0;margin: 20px auto;clear: both;">
   <!-- End main email content -->
  <?php $this->renderPartial('webroot.themes.'.Yii::app()->theme->name.'.views.layouts.mail.footer'); ?>