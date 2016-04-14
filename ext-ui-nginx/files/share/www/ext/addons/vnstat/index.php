<?php
    //
    // vnStat PHP frontend (c)2006-2010 Bjorge Dijkstra (bjd@jooz.net)
    //
    // This program is free software; you can redistribute it and/or modify
    // it under the terms of the GNU General Public License as published by
    // the Free Software Foundation; either version 2 of the License, or
    // (at your option) any later version.
    //
    // This program is distributed in the hope that it will be useful,
    // but WITHOUT ANY WARRANTY; without even the implied warranty of
    // MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    // GNU General Public License for more details.
    //
    // You should have received a copy of the GNU General Public License
    // along with this program; if not, write to the Free Software
    // Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    //
    //
    // see file COPYING or at http://www.gnu.org/licenses/gpl.html 
    // for more information.
    //
    require 'config.php';
    require 'localize.php';
    require 'vnstat.php';

    validate_input();

    require "./themes/$style/theme.php";

    function write_side_bar()
    {
        global $iface, $page, $graph, $script, $style;
        global $iface_list, $iface_title;   
        global $page_list, $page_title;
        
        $p = "&amp;graph=$graph&amp;style=$style";

        print "<table width=\"100%\"><tr>\n";
        foreach ($iface_list as $if)
        {
            print "<td><table class=\"status\">\n";
            print "<tr><th class=\"left\" colspan=\"4\">";
            if (isset($iface_title[$if]))
            {
                print $iface_title[$if];
            }
            else
            {
                print $if;
            }
            print "</th></tr><tr class=\"even\">\n";
            foreach ($page_list as $pg)
            {
                print "<td class=\"value\"><a href=\"$script?if=$if$p&amp;page=$pg\">".$page_title[$pg]."</a></td>\n";
            }
            print "</tr></table></td>\n";
        }
        print "</tr></table>\n"; 
    }
    

    function kbytes_to_string($kb)
    {
        $units = array('Тб','Гб','Мб','Кб');
        $scale = 1024*1024*1024;
        $ui = 0;

        while (($kb < $scale) && ($scale > 1))
        {
            $ui++;
            $scale = $scale / 1024;
        }   
        return sprintf("%0.2f %s", ($kb/$scale),$units[$ui]);
    }
    
    function write_summary()
    {
        global $summary,$top,$day,$hour,$month, $iface;

        $trx = $summary['totalrx']*1024+$summary['totalrxk'];
        $ttx = $summary['totaltx']*1024+$summary['totaltxk'];

        //
        // build array for write_data_table
        //
        $sum[0]['act'] = 1;
        $sum[0]['label'] = "<a href=\"/ext/addons/vnstat/index.php?if=".$iface."&graph=large&style=light&page=h\">".T('This hour')."</a>";
        $sum[0]['rx'] = $hour[0]['rx'];
        $sum[0]['tx'] = $hour[0]['tx'];

        $sum[1]['act'] = 1;
        $sum[1]['label'] = "<a href=\"/ext/addons/vnstat/index.php?if=".$iface."&graph=large&style=light&page=d\">".T('This day')."</a>";
        $sum[1]['rx'] = $day[0]['rx'];
        $sum[1]['tx'] = $day[0]['tx'];

        $sum[2]['act'] = 1;
        $sum[2]['label'] = "<a href=\"/ext/addons/vnstat/index.php?if=".$iface."&graph=large&style=light&page=m\">".T('This month')."</a>";
        $sum[2]['rx'] = $month[0]['rx'];
        $sum[2]['tx'] = $month[0]['tx'];

        $sum[3]['act'] = 1;
        $sum[3]['label'] = T('All time');
        $sum[3]['rx'] = $trx;
        $sum[3]['tx'] = $ttx;

        write_data_table(T('Summary'), $sum);
        print "<br/>\n";
        write_data_table(T('Top 10 days'), $top);
    }
    
    
    function write_data_table($caption, $tab)
    {
        print "<table class=\"status\" width=\"100%\" cellspacing=\"0\">\n";
        print "<tr class=\"odd\"><td colspan=\"4\">";
        print "<table><tr><td><b>$caption</b></td></tr></table>\n";
        print "</td></tr>";
        print "<tr>";
        print "<th class=\"left\" style=\"width:120px;\">&nbsp;</th>";
        print "<th class=\"left\">".T('In')."</th>";
        print "<th class=\"left\">".T('Out')."</th>";
        print "<th class=\"left\">".T('Total')."</th>";  
        print "</tr>\n";

        for ($i=0; $i<count($tab); $i++)
        {
            if ($tab[$i]['act'] == 1)
            {
                $t = $tab[$i]['label'];
                $rx = kbytes_to_string($tab[$i]['rx']);
                $tx = kbytes_to_string($tab[$i]['tx']);
                $total = kbytes_to_string($tab[$i]['rx']+$tab[$i]['tx']);
                $id = ($i & 1) ? 'odd' : 'even';
                print "<tr class=\"$id\">";
                print "<td class=\"value\">$t</td>";
                print "<td class=\"value\">$rx</td>";
                print "<td class=\"value\">$tx</td>";
                print "<td class=\"value\">$total</td>";
                print "</tr>\n";
             }
        }
        print "</table>\n";
    }

    get_vnstat_data();

    //
    // html start
    //
    header('Content-type: text/html; charset=utf-8');
    print '<?xml version="1.0"?>';
?>        
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title>vnStat</title>
  <link rel="stylesheet" type="text/css" href="themes/<?php echo $style ?>/style.php"/>
  <link rel="stylesheet" type="text/css" href="../../css/keenetic.css?=2971"/>
  <link rel="stylesheet" type="text/css" href="../../css/opera.css?=2971"/>
  <link rel='stylesheet' type='text/css' href='../css/addons.css'/>
  <!--[if gte IE 6]><link rel='stylesheet' type='text/css' href='../../css/ie.css?=130119030512'/><![endif]-->
  <!--[if lte IE 7]><link rel='stylesheet' type='text/css' href='../../css/ie67.css?=130119030512'/><![endif]-->
</head>
<body class="body">
<script>
function fresh() {
location.reload();
}
setInterval("fresh()",30000);
</script>
<div class="leaf">
<div class="leaf_head">Статистика сетевых интерфейсов</div>
<div class="leaf_page">
<div class="back leaf_page_shade"> </div>
<div class="leaf_page_content" id="content">

<div class="custpage">

  <?php write_side_bar(); ?>
    <table class="status"><tr class="even"><th class="left"><?php $inf=shell_exec("/sbin/ifconfig ".$iface." | grep inet"); print "$iface_title[$iface] ($iface) $inf";?></th></tr>
    <?php
    $graph_params = "if=$iface&amp;page=$page&amp;style=$style";
    if ($page != 's')
        if ($graph_format == 'svg') {
	     print "<tr class=\"even\"><td class=\"name\" align=\"center\"><object type=\"image/svg+xml\" width=\"692\" height=\"297\" data=\"graph_svg.php?$graph_params\"></object></td></tr>\n";
        } else {
	     print "<img src=\"graph.php?$graph_params\" alt=\"graph\"/>\n";	
        }
    print "</table>\n";
    if ($page == 's')
    {
        write_summary();
    }
    else if ($page == 'h')
    {   
        write_data_table(T('Last 24 hours'), $hour); 
    }
    else if ($page == 'd')
    {
        write_data_table(T('Last 30 days'), $day);	
    }
    else if ($page == 'm')
    {
        write_data_table(T('Last 12 months'), $month);   
    }
    ?>

</div>

</div>
</div>
</div>
</body>
</html>
