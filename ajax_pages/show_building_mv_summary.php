<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/system.class.php");
require_once(AbsPath."classes/projects.class.php");

$DB=new DB;
$System=new System;
$Project=new Project;

$building_id=$_GET['building_id'];
$displayType=$_GET['displayType'];

$strSQL="Select * from t_mv_baseline where building_id=".$building_id;
$baselineArr=$DB->Returns($strSQL);
if(mysql_num_rows($baselineArr)>0)
{
    while($baseline=mysql_fetch_object($baselineArr))
    {
        if($displayType == 1){
        ?>	
            <table>
                <tr>
                    <th>&nbsp;</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Sunday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Monday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Tuesday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Wednesday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Thursday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Friday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Saturday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Average</th>
                </tr>
                <tr>
                    <td style="background-color:#DDDDDD;border: 2px solid black;">00:00-06:00</td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sun_1)/293.071107; $sum = $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_mon_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_tue_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_wed_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_thu_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_fri_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sat_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></td>
                </tr>
                <tr>
                    <td style="background-color:#DDDDDD;border: 2px solid black;">06:01-12:00</td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sun_2)/293.071107; $sum = $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_mon_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_tue_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_wed_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_thu_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_fri_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sat_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></td>
                </tr>
                <tr>
                    <td style="background-color:#DDDDDD;border: 2px solid black;">12:01-18:00</td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sun_3)/293.071107; $sum = $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_mon_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_tue_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_wed_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_thu_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_fri_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sat_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></td>
                </tr>
                <tr>
                    <td style="background-color:#DDDDDD;border: 2px solid black;">18:01-00:00</td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sun_4)/293.071107; $sum = $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_mon_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_tue_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_wed_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_thu_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_fri_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sat_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></td>
                </tr>
                <tr style="background-color:#DDDDDD;">
                    <th style="background-color:#DDDDDD;border: 2px solid black;">Total</th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sun)/293.071107; $sum = $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_mon)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_tue)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_wed)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_thu)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_fri)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sat)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></th>
                </tr>
            </table>

        <?php
        }else{
        ?>
            <table>
                <tr>
                    <th>&nbsp;</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Sunday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Monday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Tuesday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Wednesday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Thursday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Friday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Saturday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Average</th>
                </tr>
                <tr>
                    <td style="background-color:#DDDDDD;border: 2px solid black;">00:00-06:00</td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_sun_1)/293.071107; $sum = $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_mon_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_tue_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_wed_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_thu_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_fri_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_sat_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></td>
                </tr>
                <tr>
                    <td style="background-color:#DDDDDD;border: 2px solid black;">06:01-12:00</td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_sun_2)/293.071107; $sum = $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_mon_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_tue_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_wed_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_thu_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_fri_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_sat_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></td>
                </tr>
                <tr>
                    <td style="background-color:#DDDDDD;border: 2px solid black;">12:01-18:00</td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_sun_3)/293.071107; $sum = $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_mon_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_tue_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_wed_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_thu_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_fri_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_sat_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></td>
                </tr>
                <tr>
                    <td style="background-color:#DDDDDD;border: 2px solid black;">18:01-00:00</td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_sun_4)/293.071107; $sum = $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_mon_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_tue_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_wed_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_thu_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_fri_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_sat_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></td>
                </tr>
                <tr style="background-color:#DDDDDD;">
                    <th style="background-color:#DDDDDD;border: 2px solid black;">Total</th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_sun)/293.071107; $sum = $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_mon)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_tue)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_wed)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_thu)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_fri)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->g_sat)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></th>
                </tr>
            </table>
        <?php    
        }
    }
}
?>