<?php
/*
Plugin Name: Friendship Meter
Plugin URI: http://developer.documentbd.com/
Description: This Friendship Meter is basically works to measure the percentage of your friendship depth. After install this plugin you will get a new "Friendship Meter" widgets on your wordpress admin panel under appearance menu. Then just use it and enjoy with your friends in your wordpress blog. You can add this meter on your wordpress post or page too by write a simple shortcode  [frinedship_meter_scale].
Author: Md Nurullah Hussain
Version: 1.0
Author URI: http://www.documentbd.com/
License: GPLv2 or later
*/

class frinedship_meter_scale {

    function meter_init() {
    	$class_name = 'frinedship_meter_scale';
    	$meter_title = 'Friendship Meter';
    	$meter_desc = ' Measurement the friendship from this Meter';

    	if (!function_exists('wp_register_sidebar_widget')) return;

    	wp_register_sidebar_widget(
    		$class_name,
    		$meter_title,
    		array($class_name, 'meter_widget'),
            array(
            	'classname' => $class_name,
            	'description' => $meter_desc
            )
        );

    	wp_register_widget_control(
    		$class_name,
    		$meter_title,
    		array($class_name, 'meter_control'),
    	    array('width' => '100%')
        );

        add_shortcode(
        	$class_name,
        	array($class_name, 'meter_shortcode')
        );
    }

    function meter_display($is_widget, $args=array()) {
    	if($is_widget){
    		extract($args);
			$options = get_option('frinedship_meter_scale');
			$title = $options['title'];
			$output[] = $before_widget . $before_title . $title . $after_title;
		}


		$output[] = '<div style="margin-top:6px;">
			<script type="text/javascript">
			function gObj(obj) {
				var theObj;
				if(document.all){
					if(typeof obj=="string"){
						return document.all(obj);
					}else{
						return obj.style;
					}
				}
				if(document.getElementById){
					if(typeof obj=="string"){
						return document.getElementById(obj);
					}else{
						return obj.style;
					}
				}
				return null;
			}
			function trimAll(sString){
				while (sString.substring(0,1) == " "){
					sString = sString.substring(1, sString.length);
				}
				while (sString.substring(sString.length-1, sString.length) == " "){
					sString = sString.substring(0,sString.length-1);
				}
				return sString;
			}
			function showquicklovemsg(inStr, isError){
				if (isError) inStr = "<font color=red>" + inStr + "</font>";
				gObj("friendmeterresult").innerHTML = inStr;
			}
			function getNum(inChar){
				outputNum = 0;
				for (i=0;i<inChar.length;i++){
					outputNum += inChar.charCodeAt(i);
				}
				return outputNum;
			}
			function friendmeter(){
				showquicklovemsg("calculating...",true);
				firstfirendname = trimAll(gObj("firstfirendname").value);
				secondfriendname = trimAll(gObj("secondfriendname").value);
				if (firstfirendname.length<1){
					showquicklovemsg("please type first friend name",true);
					return;
				}else if (secondfriendname.length<1){
					showquicklovemsg("please type second friend name",true);
					return;
				}
				firstfirendname = firstfirendname.toLowerCase();
				secondfriendname = secondfriendname.toLowerCase();
				totalNum = getNum(firstfirendname) * getNum(secondfriendname);
				finalScore = totalNum % 100;

				finalScore = "<font color=red><b>Friendship Score: " + finalScore + "%</b></font>";
				showquicklovemsg(finalScore, false);
			}
			</script>

			<!-- Meter Box Area -->
			<table>
				<form>
				<tr>
					<td>First Friend Name:<br><input type="text" name="firstfirendname" size="15" id="firstfirendname"></td>
				<tr>
				<tr>
					<td>Second Friend Name:<br><input type="text" name="secondfriendname" size="15" id="secondfriendname"></td>
				<tr>
				<tr>
					<td><input type="button" value="Check Now" onclick="friendmeter()"></td>
				<tr>
				<tr>
					<td><div id="friendmeterresult"></div></td>
				<tr>
				<tr>
					
				</tr>
				</form>
			</table>
		</div>';
    	$output[] = $after_widget;
    	return join($output, "\n");
    }

	function meter_control() {
		$class_name = 'frinedship_meter_scale';
		$meter_title = 'Friendship Meter';

	    $options = get_option($class_name);

		if (!is_array($options)) $options = array('title'=>$meter_title);

		if ($_POST[$class_name.'_submit']) {
			$options['title'] = strip_tags(stripslashes($_POST[$class_name.'_title']));
			update_option($class_name, $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);

		echo '<p>Title: <input style="width: 150px;" name="'.$class_name.'_title" type="text" value="'.$title.'" /></p>';
		echo '<input type="hidden" name="'.$class_name.'_submit" value="1" />';
	}

    function meter_shortcode($args, $content=null) {
        return frinedship_meter_scale::meter_display(false, $args);
    }

    function meter_widget($args) {
        echo frinedship_meter_scale::meter_display(true, $args);
    }
}

add_action('widgets_init', array('frinedship_meter_scale', 'meter_init'));

?>