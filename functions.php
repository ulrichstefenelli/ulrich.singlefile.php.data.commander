<?php

error_reporting(E_ERROR | E_PARSE);
global $db; global $tabnr2; global $tabnr3;
$p = dirname($_SERVER['PHP_SELF']);
#write2("/eigenes/www".$p, $o = "/eigenes/bin/l");
$con = mysqli_connect("localhost", "backuser", "backuser99", false, 128);
myq("set names 'utf8' collate 'utf8_general_ci'");

include_once("/mnt/69/eigenes/commandr/functions.rserve");

function spoilerfunction(){
	echo	"<script type='text/javascript'>
			function divaufzu(n){
				d = document;
				bu = d.getElementById('butt' + n);
				st = d.getElementById('div'  + n).style;
				if (st.display != '') {
					st.display = ''; bu.innerHTML = ' - '; bu.value = ' - ';
				} else {
					st.display = 'none'; bu.innerHTML = ' + '; bu.value = ' + ';
				}
			}		
		</script>";
}

function spoiler($text){
	$b = mt_rand(); br(1);
	echo "<div align = left style = 'margin-bottom:0px'><input id = 'butt$b' type = 'button' value = ' + ' onClick = divaufzu('$b')></div>
 		<div align = center style = 'padding: 0px; border: 1px inset; border-color: black;'><div id = 'div$b' style = 'display: none;'>$text</div></div>";
}

//columnwidths("400, 70");  //erste Spalte 400, ab 2. Spalte dann 70
//columnwidths("400, 50, 60, 70, 80, 90");  //erste Spalte 400, ab 2. Spalte dann jeweils breiter
function columnwidths($c){  //setzt mit javascript die Spaltenbreiten der Tabelle, die davor gezeigt wird
	echo	"<script type='text/javascript'>
			d = document;
			e = d.getElementsByTagName('*');
			fe = new Array(); z = 0;
			for(i = 0; i < e.length; i++) if (e[i].id.indexOf('tb_') == 0) fe[z++] = e[i].id;
			tb = d.getElementById(fe[fe.length - 1]);
			c = '$c';
			cf = c.split(',');
			gr1 = cf.length;
			gr2 = tb.rows[0].cells.length;
			if (gr1 == gr2) for(j = 0; j < gr2; j++) tb.rows[0].cells[j].width = cf[j] + 'px';
			if (gr1 !== gr2) for(j = 0; j < gr2; j++) {
				if (j < gr1) w = cf[j]; else w = cf[gr1 - 1];
				tb.rows[0].cells[j].width = w + 'px';
			}
		</script>";
}

function colleft(){  //richtet mit javascript die letzte Spalten auf links aus
	echo	"<script type='text/javascript'>
			d = document;
			e = d.getElementsByTagName('*');
			fe = new Array(); z = 0;
			for(i = 0; i < e.length; i++) if (e[i].id.indexOf('tb_') == 0) fe[z++] = e[i].id;
			
			tb = d.getElementById(fe[fe.length - 1]);			
			gr1 = tb.rows.length;
			gr2 = tb.rows[0].cells.length;			
			for(i = 0; i < gr1; i++) tb.rows[i].cells[gr2 - 1].style.textAlign = 'left';
		</script>";
}

//colleft2("0,1");
function colleft2($c = "0,1"){  //richtet Spalten 0 + 1 auf links aus
	echo	"<script type='text/javascript'>
			d = document;
			e = d.getElementsByTagName('*');
			fe = new Array(); z = 0;
			for(i = 0; i < e.length; i++) if (e[i].id.indexOf('tb_') == 0) fe[z++] = e[i].id;
			
			tb = d.getElementById(fe[fe.length - 1]);	
			gr1 = tb.rows.length;
			gr2 = tb.rows[0].cells.length;
			
			cf = [$c];
			for(i = 0; i < gr1; i++) {
				for(j = 0; j < gr2; j++) if (j in cf) tb.rows[i].cells[j].style.textAlign = 'left';
			}
		</script>";
}

function setfocus($feld){
	echo "<script language=javascript>document.forms[0].$feld.value = 'q';</script>";
}

// $t = dirlist3($d."/out/", "*.png");
// echo makedrop("png onchange = \"javascript: d.getElementById('myimg').src = 'out/' + fo.png.value;\"", explode(",", $t), $_POST["png"], "", 10);
// echo "<img id = myimg src = out/d_gr2.png width = 85% height = auto>";

function makedrop($field, $itemlist, $itemselected, $style = "", $size = 1) {
	$t = chr(10)."<select name = $field id = $field size = $size style = '$style'>";
	for($i = 0; $i < count($itemlist); ++$i) {
		$c = $itemlist[$i];
		if ($c == $itemselected) $s = "selected"; else $s = "";
		if (trim($c) !== "") $t .= chr(10)."<option $s value = '$c'>".$c."";
	}
	$t .= "</select>".chr(10);
	#md($s);
	return $t;
}

function makedrop2($field, $itemlist, $valuelist, $itemselected, $size = 1) {
	$t = chr(10)."<select name = $field id = $field size = $size>";
	for($i = 0; $i < count($valuelist); ++$i) {
		$c = $valuelist[$i];
		if ($c == $itemselected) $s = "selected"; else $s = "";
		if (trim($c) !== "") $t .= chr(10)."<option $s value = '$c'>".$itemlist[$i];
	}
	$t .= "</select>".chr(10);
	return $t;
}

function makedrop_multiple($field, $itemlist, $listselected, $size, $style) {
	$t = chr(10)."<select name = $field multiple size = $size style = '$style' >";
	for($i = 0; $i < count($itemlist); $i++) {
		$c = $itemlist[$i];
		if (inlist2($listselected, $c)) $s = "selected"; else $s = "";
		$t .= "<option $s value = '$c'>".fromtolast($c, "/", "");
	}
	$t .= "</select>";
	return $t;
}

function getfromtab_javascript($r, $c){ //liest aus letzter obiger Tabelle und schreibt Wert
	echo	"<script type='text/javascript'>
			d = document;
			e = d.getElementsByTagName('*');
			fe = new Array(); z = 0;
			for(i = 0; i < e.length; i++) if (e[i].id.indexOf('tb_') == 0 && e[i].id.indexOf('_r') == -1) fe[z++] = e[i].id;
			tb = d.getElementById(fe[fe.length - 1]);
			document.write(document.getElementById(tb.id + '_r".$r."c".$c."').innerHTML);
		</script>";
}

function getfromtab($fi, $r, $c){
	$fe = read2d($fi.".raw");
	show($fe);
	echo $fe[$r][$c];
	return;
	//show($fe);
	
	if ($r !== "max" and $c !== "max") return $fe[$r][$c];
	if ($r !== "max" and $c  == "max") return $fe[$r][count($fe[0]) - 1];	
	if ($r  == "max" and $c !== "max") return $fe[count($fe) - 1][$c];
	if ($r  == "max" and $c  == "max") return $fe[count($fe) - 1][count($fe[0]) - 1];	
}

function pa($up = -1){
	$f = dirname($_SERVER['PHP_SELF']);
	$fe = explode("/", $f);
	for($i = 0; $i < count($fe) + $up; ++$i) $t[] = $fe[$i];
	return implode("/", $t);
}

//____________________ voll funktionierendes Beispiel _______________________
// $fe = getrnd(15, 10); comp($fe, "if (@c1@ < 50) @gruppe@ = 0; else @gruppe@ = 1;");
// $fe = agg($fe, "gruppe", "^c", "mean2,count2");
// $fe = function_on_fe($fe, "m", "format2(@, '0.0')");
// show($fe);
function alt_agg($fe, $uv, $av, $fu){
	$uvf = vl($fe[0], $uv); $ugr = count($uvf); $uv0 = $uv;
	$avf = vl($fe[0], $av); $avz = count($avf);
	$fuf = explode(",", $fu);

	if ($ugr > 0) {
		$t = "tmp"; $uv = $t;
		comp($fe, "@tmp@ = ';'.@".implode("@.';'.@", $uvf)."@.';' ;");
	}
	
	$m = 99;
	$fe = getmat2($fe, $uv."|".$av);
	$fe = reco2($fe, $uv, " = $m");
	
	$st = uvstufen($fe, $uv, "|");
	$stf = explode("|", $st); 
	
	$o[0][0] = $uv;
	for ($s = 0; $s < count($stf); ++$s){
		$u = $stf[$s];
		$o[$s + 1][0] = $u;
		$z = 0;
		for ($j = 0; $j < $avz; ++$j){
			$a = $avf[$j];
			for ($f = 0; $f < count($fuf); ++$f){
				$fu = $fuf[$f];
 				$o[0][$z + 1] = $a.$fu;
				$se = selif3($fe, "@$uv@ == '$u'");
				$fe2 = vector($se, "^$a$");
				eval("\$o[\$s + 1][\$z + 1] = $fu(\$fe2);");
				++$z;
			}
		}
	}
	
	if ($ugr > 0) {
		for ($j = 0; $j < $ugr; ++$j) comp($o, "@".$uvf[$j]."@ = explode(';', @".$t."@)[".($j + 1)."];");
		$o = spalteloeschen2($o, $t);
	}
	$o = getmat2($o, $uv0.",".$av);
	//for ($j = 0; $j < count($o[0]); ++$j) $o[0][$j] = givelabel($o[0][$j]);
	return $o;
}

function r_median($v, $tb, $ci = 0.95){
	$l = chr(10);
	$o = "/tmp/r.cmd";
	if (file_exists($o)) unlink($o);
	$v = strtoupper($v);
	$t  = " median.ci <- function(x, conf.level = $ci){n <- length(x); k <- 1; while (1 - 2 * pbinom(k - 1, n, 0.5) >= conf.level) k = k + 1; k <- k - 1; x.sort <- sort(x); return(c(median(x), x.sort[k], x.sort[n-k+1], length($v)))}; $l";
	$t .= " library(DBI); $l library(RMySQL); $l con <- dbConnect(MySQL(), user='backuser', password='backuser99', dbname='".currdb()."', host='localhost'); $l y <- dbGetQuery(con, paste(\"select $v from $tb where $v <>'' \")); $l attach(y); $l median.ci($v); ";
	write2($t,$o);
	exec("R --vanilla -q < $o > $o.out");
	$c = file_get_contents("$o.out");
	
	$c = fromto($c, "[1]", chr(10));
	$c = preg_replace('/\s{2,}/',' ', $c);
	return trim($c);
}

function r_ci($v, $tb, $ci = 0.95){
	$l = chr(10);
	$o = "/tmp/r.cmd";
	if (file_exists($o)) unlink($o);
	$v = strtoupper($v);

	$t  = " library(PropCIs); $l library(DBI); $l library(RMySQL); $l con <- dbConnect(MySQL(), user='backuser', password='backuser99', dbname='".currdb()."', host='localhost'); $l";
	$t .= " x <- dbGetQuery(con, paste('select v9, count(v9) from test where not isnull(v9) group by v9')); $l";	
	$t .= " addz2ci(x[2,2], x[1,2] + x[2,2], 0.95); ";	

	write2($t,$o);
	exec("R --vanilla -q < $o > $o.out");	
	$c0 = file_get_contents("$o.out");

	$c  = fromto($c0, "[1]", chr(10));
	$c .= fromto($c0, "interval:".chr(10), chr(10));	
	return $c;
}

//____________________ voll funktionierendes Beispiel _______________________
//$fe = getrnd3(20, 5); $fe = comp3($fe, "is_even(@c1@) ? @gr@ = 1 : @gr@ = 0;"); show3(r_mw($fe, "c2", "gr"));
function r_mw($fe, $av, $uv){
	$l = chr(10);
	$fe = get3($fe, $av.",".$uv); $te = "/eigenes/downs/temp";
	$f = $te."/tmp.dat";
	export::asc($fe, $f);
	$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x)); options(warn=-1);".$l;
	$r .= "w <- wilcox.test(x\$$av ~ x\$$uv, data = x, conf.int = TRUE);".$l;
	$r .= "o <- cbind(w\$p.value, w\$estimate, w\$conf.int[1], w\$conf.int[2]);".$l;
	$r .= "colnames(o) <- c('p', 'estimate', 'lower', 'upper');".$l;
	$o = $te."/tmp.out";
	$r .= "write.table(o, file = '$o', sep = '\\t', quote = F, row.names = F); ".$l;
	$fi = $te."/r.cmd"; write2($r, $fi);
	
	#$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); exec("sudo Rscript '$fi'");
	show3(read3d($o));
	return function_on_fe3(read3d($o), ".", "format2(@, '0.0000')");
}

//____________________ voll funktionierendes Beispiel _______________________
// $fe = getrnd3(100, 5, 1, 10);
// $fe = comp3($fe, "switch (1){case (@c1@ <= 3): @g@ = 1; break; case (@c1@ >= 3 and @c1@ <= 7): @g@ = 2; break; case (@c1@ >= 7): @g@ = 3; break; }");
// freq3($fe, "g");
// means3($fe, "g$", "c2", "mean2,sd2,min2,median2,max2,count2");
// show(r_kw($fe, "c2", "g"));
// #show3($fe);
function r_kw($fe, $av, $uv){  //Kruskal-Wallis-Test über R
	$fe = get3($fe, $av.",".$uv); $te = "/eigenes/downs/temp";
	$f = $te."/tmpkw.dat";
	$fe2 = writefe(flip3($fe), $f, 1);
	$l = chr(10);
	$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
	$r .= "k <- kruskal.test($av ~ $uv, data = x); ".$l;
	$r .= "t <- cbind('kw', k\$p.value, k\$statistic, k\$parameter, count2(x)); colnames(t) <- c('test', 'p', 'chi', 'df', 'n'); ".$l;	
	$o = $te."/tmpkw.out"; $r .= "write.table(t, file = '$o', sep = '\\t', quote = F, row.names = F); ".$l;
	
	#$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); exec("sudo Rscript '$fi' '".$fi.".log' ");
	
	$fe2 = read3d($o);
	$fe2 = data::filter($fe2, "@p@ <> ''");
	$fe2 = data::fu($fe2, ".", "format2(@, '0.00')");
	return $fe2;
}

// $fe = getrnd(10, 3);
// comp($fe, "if (@c1@ < 50) @gruppe@ = 0; else @gruppe@ = 1;");
// showneu(agg($fe, "gruppe", "c3", "mean2,count2"));
// show(r_mw_equiv($fe, "c3", "gruppe", 0, 10));
function r_mw_equiv($fe, $av, $uv, $refgruppe, $delta){
	$fe = getmat2($fe, $av.",".$uv);
	comp($fe, "if (@gruppe@ == $refgruppe) {@".$av."plus@ = @$av@ + $delta; @".$av."minus@ = @$av@ - $delta; } else {@".$av."plus@ = @$av@; @".$av."minus@ = @$av@;}");	
	#show($fe);
	$o = agg($fe, $uv, $av, "mean2");
	$o = function_on_fe($o, ".", "round(@, 1)");
	$o = sort2($o, $uv);
	#show($o);
	$ciu = "<b>".$o[1][2]."</b> - ".$o[1][3];
	$cil = $o[1][2]." - <b>".$o[1][3]."</b>";
	
	$fe = addarr(r_mw($fe, $av."plus" , $uv), r_mw($fe, $av."minus", $uv));
	$fe = selif3($fe, "@estimate@ <> 'estimate'");
	comp($fe, "@p@ = @p@/2;");
	$fe = function_on_fe($fe, ".", "format2(@, '0.0000')");
	
	$fe = getmat2($fe, "^(?!lower|upper)");
	$fe[0][1] = "delta_um_".$refgruppe;
	$fe[1][1] = $ciu;
	$fe[2][1] = $cil;
	
	$fe[0][2] = "mean_vs_bold";
	$fe[1][2] = $o[2][1];
	$fe[2][2] = $o[2][1];
	
	show($fe, 2);
}

//$t1 = "1,1,1,1,1,1,2,2,2,2,2";
//$t2 = "5,6,7,9,8,1,3,4,5,1,2";
//$fe = textin2dimfeld($t1, $t2);
//show($fe);
//md(utest($fe));
function utest($fe){ //uv, av
	$fe[0][0] = "uv";
	$fe[0][1] = "av";
	
	$fe = selif3($fe, "uv <>'' and av <> '' ");
	$st = uvstufen($fe, "uv"); $stf = explode(",", $st);
	
	$fe = sort2($fe, "av");
	$fe = lfn($fe); $fe[0][0] = "rank";

	$meanr = agg($fe, "av", "rank", "mean2,count2");
	$fe = ordervars($fe, "av,uv,rank"); //show($fe);
	$fe = merge($fe, $meanr);
	
	$ti = 0;
	for($i = 0; $i < count($meanr); ++$i){
		if ($meanr[$i][2] > 1) $ti = $ti + (pow($meanr[$i][2], 3) - $meanr[$i][2]) / 12;
	}
	
	for($i = 1; $i < count($fe); ++$i){
		if ($fe[$i][1] == $stf[0]) {$t1 = $t1 + $fe[0][3]; ++$n1; $w1 = $w1 + $fe[$i][0];}
		if ($fe[$i][1] == $stf[1]) {$t2 = $t2 + $fe[0][3]; ++$n2; $w2 = $w2 + $fe[$i][0];}
	}
	$m1 = $w1 / $n1; $m2 = $w2 / $n2;	
	$u = $n1 * $n2 + $n1 * ($n1 + 1) / 2 - $ti;
	if ($u > $n1 * $n2 / 2) $u = $n1 * $n2 - $u;
	$n = $n1 + $n2;
	$sigma = sqrt($n1 * $n2 / ($n * ($n - 1)) * ((pow($n, 3) - $n) / 12 - $ti));
	$z = ($u - $n1 * $n2 / 2) / $sigma;
	$p = 2 * (1 - cumnormdist($z));
	return sign($p, 3);
}

//____________________ voll funktionierendes Beispiel _______________________
// $fe = getrnd3(100, 2); $fe = comp3($fe, "is_even(@c1@) ? @gr1@ = 1 : @gr1@ = 0;"); $fe = comp3($fe, "is_even(@c2@) ? @gr2@ = 1 : @gr2@ = 0;"); show3(r_odds($fe, "gr1", "gr2"));
function r_odds($fe, $uv1, $uv2){
	$fe = data::get($fe, $uv1.",".$uv2);
	$l = chr(10);
	$f = "/tmp/tmp.dat"; export::asc($fe, $f);
	$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x)); options(warn = -1);".$l;
	$r .= "f <- fisher.test(x\$".$uv1.", x\$".$uv2.", workspace = 2e+07, hybrid = TRUE);";
	$r .= "o <- cbind(paste(f\$p.value, ''), paste(f\$estimate, ''), paste(f\$conf.int[1], ''), paste(f\$conf.int[2], ''));".$l;
	$r .= "colnames(o) <- c('p', 'odds', 'lower', 'upper');".$l;
	$o = "/tmp/tmp.out"; if (file_exists($o)) unlink ($o);
	$r .= "write.table(o, file = '$o', sep = '\\t', quote = F, row.names = F); ".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi);
	$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	$fe2 = read3d($o);
	$fe2 = data::fu($fe2, ".", "num::format(@, 4)");
	return $fe2;
}

// $fe = getrnd3(100, 2); $fe = comp3($fe, "is_even(@c1@) ? @gr1@ = 1 : @gr1@ = 0;"); $fe = comp3($fe, "is_even(@c2@) ? @gr2@ = 1 : @gr2@ = 0;"); show3(r_odds($fe, "gr1", "gr2"));
function r_utest($fe, $a, $u){
	$fe = data::get($fe, "^".$a."$,^".$u."$");
	$l = chr(10);
	$f = "/eigenes/downs/temp/r_utest.dat"; export::asc($fe, $f);
	$r .= "x <- read.table('$f', header = T, fill = T); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x)); options(warn = -1);
	       k <- kruskal.test($a ~ $u, data = x); y = data.frame(k\$p.value); colnames(y) = c('p');
	       write.table(y, file = '$f.out', sep = '\\t', quote = F, row.names = F); ".$l;
	write2($r, $f.".r");
	$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	$o = read3d($f.".out");
	$o = data::filter($o, "@p@ !== ''");
	$o = data::comp($o, "@p@ = round(@p@,3);");
	return $o;
}

//____________________ voll funktionierendes Beispiel _______________________
// $fe = getrnd3(100, 5, 1, 10);
// $fe = comp3($fe, "switch (1){case (@c1@ <= 3): @g@ = 1; break; case (@c1@ >= 3 and @c1@ <= 7): @g@ = 2; break; case (@c1@ >= 7): @g@ = 3; break; }");
// freq3($fe, "g"); means3($fe, "g$", "c2", "mean2,sd2,min2,median2,max2,count2");
// // show(r_cohensd($f*/e, "c2", "g"));
#$fe = getmat3("select * from daten"); #show3($fe);
#freq3($fe, "gr");
#means3($fe, "gr$", "v12", "mean2,sd2,count2");
#show(r_cohensd($fe, "v12", "gr"));
function r_cohensd($fe, $av, $uv){  //Cohen's D
	$fe = get3($fe, $av.",".$uv);
	$f = "/tmp/tmp_".$av.".dat"; $l = chr(10);
	$fe2 = writefe(flip3($fe), $f, 1);
	$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
	$r .= "library(stddiff); c <- stddiff.numeric(x, gcol = 2, vcol = 1);".$l;
	$o = "/tmp/tmp.out"; $r .= "write.table(cbind(c('$av'), c), file = '$o', sep = '\\t', quote = F, row.names = F); ".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	$fe2 = read2d($o);
	$fe2 = selif3($fe2, "@stddiff@ <> ''");
	$fe2 = function_on_fe($fe2, ".", "format2(@, '0.00')");
	return $fe2;
}

// md(r_quantile($fe, "v01", .50));
function r_quantile($fe, $av, $q){
	$l = chr(10);
	$fe = data::get($fe, $av."$");
	$f = "/eigenes/downs/temp/tmp.dat"; 
	export::asc($fe, $f);
	
	$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); q = quantile(x\$$av, c($q), na.rm = TRUE); rm(x);";
	$r .= "write.table(cbind(c('$av'), q), file = '$f.out', sep = '\\t', quote = F, row.names = F);".$l;
	
	write2($r, $f.".r"); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	#write2($r, $f.".r"); exec("sudo Rscript --vanilla '$f.r' > '$f.log' 2>&1 "); 
	
	$fe = read3d($f.".out");
	#show3($fe);
	return $fe["q"][0];
}

//____________________ voll funktionierendes Beispiel _______________________
// $fe = getrnd(100, 3);
// comp($fe, "@zeit@ = @c1@;"); comp($fe, "@event@ = @c3@;", $tb); comp($fe, "@gruppe@ = @c2@;");
// comp($fe, "if (@event@ < 50) @event@ = 0; else @event@ = 1;");
// comp($fe, "if (@gruppe@ < 50) @gruppe@ = 0; else @gruppe@ = 1;");
// $tb = "tmp"; push($fe, $tb);
// show(r_logrank($fe, "event", "zeit", "gruppe"));
function r_logrank($fe, $status, $time, $gruppe){
	$fe = getmat2($fe, $status.",".$time.",".$gruppe);
	$l = chr(10);
	$f = "/tmp/tmp.dat";
	writefe($fe, $f);
	$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
	$r .= $l."library(survival); library(scales); l <- survdiff(Surv(zeit, event) ~ gruppe, x); pval <- pchisq(l\$chisq, length(l\$n) - 1, lower.tail = FALSE);".$l;
	$r .= "o <- cbind(pval, l\$chisq);".$l;
	$r .= "colnames(o) <- c('p', 'Chi²');".$l;
	$o = "/tmp/tmp.out";
	$r .= "write.table(o, file = '$o', sep = '\\t', quote = F, row.names = F); ".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi);
	$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	$fe2 = read2d($o);
	$fe2 = selif3($fe2, "@p@ <> ''");
	$fe2 = function_on_fe($fe2, ".", "round(@, 4)");
	return $fe2;
}

//____________________ voll funktionierendes Beispiel _______________________
#$fe = getrnd(30, 2);
#showneu(r_wilc($fe, "c1", "c2"));
function r_wilc($fe, $av1, $av2){  //Wilcoxon-Test über R
	$l = chr(10);
	$f = "/tmp/tmp_wilc.csv";
	writefe($fe, $f, 1);
	$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
	$r .= "w <- wilcox.test(x\$$av1, x\$$av2, paired = TRUE, conf.int = TRUE);".$l;
	$r .= "o <- data.frame(cbind('$av1', '$av2', w\$p.value, w\$estimate, w\$conf.int[1], w\$conf.int[2]));".$l;
	$r .= "colnames(o) <- c('av1', 'av2', 'p', 'estimate', 'lower', 'upper');".$l;
	$o = "/tmp/tmp_wilc.out";
	$r .= "write.table(o, file = '$o', sep = '\\t', quote = F, row.names = F); ".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi);
	$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	$fe2 = read2d($o);
	#show($fe2);
	$fe2 = selif3($fe2, "@p@ <> ''");
	$fe2 = function_on_fe($fe2, ".", "format2(@, '0.000')");
	return $fe2;
}

function real2($v, $tb, $set_non_num = ""){
	$db = currdb();
	$wt = " where ";
	if (instr($tb," where ")) {
		$wt = " and ";
		$wh = " where ".fromto($tb, " where ", "");
		$tb = fromto($tb, "", " where ");
	}
	$v = vl4($v, $tb);
	$fe = explode(",", $v);
	$gr = count($fe);
	for ($i=0; $i < $gr; ++$i){
		$n = $fe[$i];
		$ty = recwert("select data_type from information_schema.columns where table_schema = '$db' and table_name = '$tb' and column_name = '$n' ");
		if ($ty == "text") {
			myq("update $tb set $n = '$set_non_num' $wh $wt not mysql.isnumeric($n)");
			myq("update $tb set $n = null $wh $wt $n = ''");
			myq("alter table $tb change $n $n real null");
		}
	}
}

function int2($v, $tb){
	$v = vl4($v);
	$fe = explode(",",$v);
	$gr = count($fe);
	for ($i = 0; $i < $gr; ++$i){
		$n = $fe[$i];
		//echop($n);
		myq("update $tb set $n = null where not mysql.isnumeric($n)");
		$l = recwert("select max(length($n)) from $tb");
		myq("alter table $tb change $n $n int($l)");
	}
}

function old_order2($tb, $b){
	drop2("^lfn$", $tb);
	$b = vl2($b, $tb);
	myq("alter table $tb order by $b");
	myq("alter table $tb add column lfn int auto_increment, add primary key (lfn)", 0);
}

function alt_lfnneu($tb){
	drop2("^lfn$", $tb);
	myq("alter table $tb add column lfn int auto_increment, add primary key (lfn)",1);
}

function alt_lfn($fe){
	$fe = colinsert($fe, "lfn", 0);
	for ($i = 1; $i < count($fe); ++$i) $fe[$i][0] = ++$z;
	return $fe;
}

function dropt2($n){
	$t = tablist2($n);	
	if ($t <> "") myq("drop table ".$t);
}

function dropt2b($tb){
	$db = currdb();
	if ($tb == "") return;
	$q = "select table_name from information_schema.tables where table_schema = '$db' and instr('$tb', table_name) > 0";
	$t = kette($q);
	if ($t<>"") myq("drop table $t");
}

function tablist2($tb){
	$db = currdb();
	$i = "information_schema";
	$t = "table_name";
	$tb = preg_replace("/,/", "|", $tb);
	$q = "select $t from $i.tables where table_schema = '$db' and $t REGEXP '$tb'";
	return kette($q, ",");
}

function tabda($tb){
	$db = currdb();
	$t = recwert("select table_name from information_schema.tables where table_schema = '$db' and preg_position('/$tb/', table_name) > 0");
	if (trim($t) !== "") return true; else return false;
}

#$a1 = "2014-06-12"; $a2 = "2014-07-01";
#$origin = new DateTime($a2);
#$target = new DateTime($a1);
#$interval = $origin->diff($target);
#echo $interval->format('%R%a days');
#md(datediff3($a1, $a2));

# !!!!!!! $d1 = "smstart"; $d2 = "smend"; $o = "zeit"; $fe = data::comp($fe, "if (trim(@$d1@) !== '' and trim(@$d2@) !== '') @$o@ = datediff(@$d1@, @$d2@);");

function datediff3($beg, $end){
	$origin = new DateTime($end);
	$target = new DateTime($beg);
	$interval = $origin->diff($target);
	if (trim($beg) !== "" and trim($end) !== "") return $interval->format('%R%a');
}

// md(daysBetweenDates("2020-01-31","2020-12-31"));

// $d = "v05"; $fe = data::comp($fe, "\$a = explode('_', @$d@); if(@$d@ !=='') @".$d."d@ = \$a[2].'-'.format2(\$a[1], '00').'-'.format2(\$a[0], '00');");
// $d = "v07"; $fe = data::comp($fe, "\$a = explode('_', @$d@); if(@$d@ !=='') @".$d."d@ = \$a[2].'-'.format2(\$a[1], '00').'-'.format2(\$a[0], '00');");
// $fe = data::comp($fe, "if (@v07@ !=='') @uezeit@ = round(datediff(@v07d@, @v05d@)/30.25);");

function datediff($startDate, $endDate) {
	$startDate = new \DateTime($startDate);
	$endDate   = new \DateTime($endDate);
	if (trim($startDate) !== '' and trim($endDate) !== '') return $startDate->diff($endDate)->days; else return "";
}

// md(datediff2(new DateTime("2021-01-15"), new DateTime("2021-01-01"), false));
function datediff2(DateTime $date1, DateTime $date2, $absolute = true){
	$interval = $date2->diff($date1);
	return (!$absolute and $interval->invert) ? - $interval->days : $interval->days;
}

function dateconvert($d, $toformat){
	$a = explode("/", $d); $a[1] = monnr($a[1]);
	$d = $a[2]."-".$a[1]."-".$a[0];
	$w = date('D', strtotime($d));
	return $w.lz(1).$a[0].".".$a[1];
}

//pf(isdate2("30/02/2004",array(0,1,2),"/")); die;
//ex3("[var_ok]=isdate2([var],array(1,0,2),'/');",$tb);
function isdate2($date,$array_dmy,$tx="."){
	$fe = explode($tx,$date);
	$a = $array_dmy;
	$d = $fe[$a[0]];
	$m = $fe[$a[1]];
	$y = $fe[$a[2]];	
	if ($m=="" or $d=="" or $y=="") return 0;
	$c = checkdate($m,$d,$y);	
	if ($c==1) return 1; else return 0;
}

function create_isdate_function(){
	myq("use mysql");
	myq("drop function if exists isdate");
	myq("create function isdate (mydate varchar(20), myformat varchar(20)) 
	     returns tinyint(1) deterministic 
		 return str_to_date(mydate,myformat) is not null;");
}

function create_givelabel_function(){
	myq("use mysql");
	myq("drop function if exists givelabel");
	myq("create function givelabel (myvar varchar(50), mycode varchar(10), myset int) 
	     returns varchar(100) deterministic 
		 return select str_to_date(mydate,myformat) is not null;");
}

/*$f = fromto(basename($_SERVER['PHP_SELF']),"",".php");
$p = dirname($_SERVER['PHP_SELF']);
$tb = preg_replace("/\./","_",$f);
get_ascii_all($p."/".$f,"*.dat",$tb); */
function get_ascii_all($pfad, $sternpunkt, $prefix = "", $name = 0){		
	$d = dirlist($pfad,$sternpunkt);
	$fe = explode(",",$d);
	for ($i=0; $i<count($fe); ++$i){
		$n0 = $fe[$i];				
		$n1 = preg_replace("/.dat$/","",$prefix."_".$n0);		
		get_ascii($pfad."/".$n0,$n1);
		if ($name==1) comp2("filename='$n0'",$n1,"varchar(30)");
	}	
}

//get_ascii("/tmp/test.dat","yyy2c");
function get_ascii($f, $tb, $db, $minchars = 30){
	$v = read1stline($f);
	$v = preg_replace('/[ÖöÜüÄäß]/', '', $v);
	$fe = explode(chr(9), $v);
	for ($i = 0; $i < count($fe); ++$i) if ($fe[$i] == "") $fe[$i] = "col".($i + 1);
	myq("drop table if exists $db.$tb", 0);
	myq("create table $db.$tb (".implode(" varchar($minchars), ",$fe)." varchar($minchars)) engine = myisam ", 1);
	myq("load data local infile '$f' into table $db.$tb fields terminated by '\\t' lines terminated by '\\n' ignore 1 lines", 0);
	$t = "";
	for ($i=0; $i<count($fe); ++$i){
		$v = $fe[$i];
		$t = $t." $v = replace($v, char(13), '')";
		if ($i < count($fe) - 1) $t = $t.",";
	}
	myq("update $db.$tb set $t",0);
	myq("alter table $db.$tb add column lfn int auto_increment, add primary key (lfn)",0);
}

//ntiles2("c1",4,$tb);
function ntiles2($v,$ntiles,$tb,$suffix="p"){
	$o=$v.$suffix;
	myq("alter table $tb order by $v");
	$a="_temp_alle_";
	comp2($a."=1",$tb);
	$i = "_temp_index_";	
	index2($i,"^$a$",$tb);
	comp2($o."=null",$tb);	
	$mx = recwert("select max($i) from $tb")+0.5;		
	myq("update $tb set $o = truncate($i/($mx/$ntiles),0)+1",0);	
	drop2("^$a$,^$i$",$tb);
}

//$tb="ggg1"; zufallsdaten4(25,5,0,0,100); rank2("c1",$tb); showt($tb." order by c1"); die;
function rank2($v, $tb, $suffix="r"){
	$rs = myq("select $v,lfn from $tb order by $v",0);
	$o = $v.$suffix;
	comp2("$o=null", $tb);
	$mr = "midrank_temp";
	comp2("$mr=null", $tb);
	
	$pr=-99999999.99; $z=0; $y=0;
	while($row = mysql_fetch_row($rs)){
		$r=$row[0];
		$l=$row[1];
		$z=$z+1;
		if ($r<>$pr and $z>1) $y=$y+1;
		myq("update $tb set $mr = $y where lfn=$l", 0);
		myq("update $tb set $o  = $z where lfn=$l", 0);
		$pr=$r;
	}
	
	$tb2 = $tb."_midrank_temp_";
	myq("drop table if exists $tb2");
	$q = "create table $tb2 select * from $tb left join (select avg($o) as ".$o."_tmp, $mr as mr from $tb group by $mr) as tmp on $tb.$mr=tmp.mr";
	myq($q);
	myq("drop table if exists $tb");
	myq("alter table $tb2 rename $tb");
	drop2("^mr$,^$mr$,^$o$", $tb);
	myq("alter table $tb change ".$o."_tmp $o real");
}

//$fe = index($fe, "^c1");
function alt_index($fe, $vars){
	$c = getcols($fe[0], $vars);
	$v = getvars($fe[0], $vars); $vf = explode(",", $v);
	$fe = sort2($fe, $vars);
	
	comp($fe, "@id_tmp@ = @".implode("@.@", $vf)."@;");
	$c1 = getcols($fe[0], "^id_tmp$");
	comp($fe, "@recnum@ = 1;");
	$c2 = getcols($fe[0], "^recnum$");
	for ($i = 2; $i < count($fe); ++$i) if ($fe[$i][$c1] == $fe[$i - 1][$c1]) $fe[$i][$c2] = $fe[$i - 1][$c2] + 1;
	$fe = spalteloeschen2($fe, "^id_tmp$");
	return $fe;
}

//index2("recnum","^c[12]$",$tb); showv("lfn,^c[12]$",$tb,1); die;
function alt_index2($ind, $uv, $tb){ //laufender index innerhalb der Gruppierung, index2("newindexname", "create_over_vars", $tb);
	myq("alter table $tb order by $uv asc");
	$u = explode(",", $uv);
	$g = "_x_tmp_x_";
	comp2("$g = concat(".implode(",'_',", $u).")", $tb, "varchar(20)");
	
	$rs = myq("select $g, lfn from $tb", 0);
	comp2("$ind = null", $tb);
	$pr=-99999999.99;
	while($row = mysql_fetch_row($rs)){
		$r=$row[0];
		$l=$row[1];
		if ($r==$pr) $z=$z+1; else $z=1;
		myq("update $tb set $ind = $z where lfn = $l");
		$pr=$r;
	}
	drop2($g, $tb);
}

//comp2("alle = 1", $tb); index3("alle_index","alle",$tb);
//index3("c12i","c1,c2",$tb);
function alt_index3_alt($ind, $v, $tb){  //erstell einen laufenden Index, auch über Gruppen  index3("indexname","gruppenvar1,gruppenvar2", $tb);
	$v = varlist_ereg4(kontrolliere($v, 1), $tb);
	comp2("_lfn_ = lfn", $tb);
	drop2("^lfn$,^$ind$", $tb);
	myq("alter table $tb add column _tmp_ int auto_increment, add primary key ($v, _tmp_)", 1);	
	comp2("$ind = _tmp_", $tb); myq("alter table $tb modify _tmp_ int not null"); myq("alter table $tb drop primary key"); drop2("^_tmp_$", $tb);
	comp2("lfn = _lfn_", $tb); myq("alter table $tb add primary key (lfn)"); drop2("^_lfn_$", $tb);
}

function implode2($fe, $tx1 = "", $tx2 = ""){
	if ($tx1 == "") $tx1 = chr(9);
	if ($tx2 == "") $tx2 = chr(10);
	for ($i = 0; $i < count($fe[0]); ++$i){
		for ($j = 0; $j < count($fe); ++$j){
			$t = $t.$fe[$j][$i].$tx1;
		}
		$t = $t.$tx2;
	}
	return $t;
}

// write2("abc", "/tmp/tmp.txt");
function write2($t, $o = "/tmp/out.asc"){ fwrite(fopen($o,"w+"),$t); }

function read2($f){ $t = file_get_contents($f); return $t; }

//read_access("/tmp/access.mdb", "tabelle1", "/tmp/tabelle1.csv", "a");
// $mdb = "/eigenes/www/$db/dat/ivo.mdb";
// exec("mdb-tables -d ';' $mdb", $out);
// $fe = implode("", $out);
// $tf = explode(";", $fe); $su = array("a", "e", "f");
// for ($j = 0; $j < count($tf); ++$j) {$tb = $tf[$j]; if (trim($tb) !== "") read_access($mdb, $tb, "$mdb.$tb.csv", $su[$j]); }
function read_access($mdb, $tb, $csv, $prefix = "v"){
	$t = chr(9);
	exec("mdb-export -d '$t' -Q $mdb '$tb' > '$csv'");   #apt-get install mdbtools
	
	$li = read1stline($csv);
	$fe = explode($t, $li);
	$gr = count($fe);

	$te = read2($csv);	
	for($j = 1; $j <= $gr; ++$j) $v[] = $prefix.$j;
	write2(implode($t, $v).chr(10).$te, "$mdb.$tb.csv");
}

// $f1 = "daten.sav";
// $fe = read_spss($d."/dat/$f1");
// $fe = recode($fe, ".", "'^Nein$' = 0 | '^Ja$' = 1");
// $fe = recode($fe, "^Geschl", "'^weiblich$' = 2 | '^männlich$' = 1");
// show($fe); die;
function read_spss($f, $tb){
	$l = chr(10);
	#$r .= "con <- dbConnect(MySQL(), user='backuser', password='backuser99', dbname='$db', host='localhost');".$l;
	$r .= "library(foreign); x <- read.spss ('$f', to.data.frame = TRUE, use.value.labels = FALSE, use.missings = FALSE);".$l;
	$o = "/tmp/read_spss.txt"; if (file_exists($o)) unlink ($o);
	$r .= "write.table(x, file = '$o', sep = '\\t', quote = F, row.names = FALSE); ".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	return read2d($o);
}

function read1stline($f){
	$l = fgets(fopen($f, "r"), 444096);
	$l = str_replace(chr(10),"",$l);
	$l = str_replace(chr(13),"",$l);
	return $l;
}

function read2d($f){  //liest tab-delim ascii in 2-dim Feld
	if (!file_exists($f)) return "File leider nicht gefunden ($f) ....";
	$resultF = fopen($f, "r");
	$tx = chr(9); if (instr($f, "albrecht_und_schablauer_2021_02")) $tx = "|";
	while(!feof($resultF)){
		$line = fgets($resultF);
		$line = preg_replace("/".chr(10)."/", "", $line);
		$fe[] = explode($tx, $line);
	}
	return $fe;
}

function read3d($f){
	$fe = read2d($f);
	for ($j = 0; $j < count($fe[0]); ++$j){
		$v = trim($fe[0][$j]); $z = -1;
		for ($i = 1; $i < count($fe); ++$i) {++$z; $o[$v][$z] = $fe[$i][$j];}
	}
	return $o;
}

function flip3($fe){  // to field with header
	while (list($k, $zei) = each($fe)) $z1[0][] = $k;
	$fe = array_values($fe);
	for ($i = 0; $i < count($fe[0]); ++$i) {
		for ($j = 0; $j < count($fe); ++$j) $o[$i][$j] = $fe[$j][$i];
	}
	array_unshift($o, $z1[0]);
	return $o;
}

function flipn($fe){ // to field without header
	for ($j = 0; $j < count($fe[0]); ++$j){
		$v = $fe[0][$j];
		for ($i = 1; $i < count($fe); ++$i) $o[$v][$i - 1] = $fe[$i][$j];
	}
	return $o;
}

//$fe = getmat3("select * from daten"); show3($fe);
function getmat3($q){
	$rs = myq($q);
	$c = mysql_num_fields($rs);
	$z = -1;
	while($row = mysql_fetch_row($rs)){		
		++$z;
		for ($j = 0; $j < $c; ++$j) $fe[mysql_field_name($rs, $j)][$z] = $row[$j];
	}
	return $fe;
}

// $fe = get3($fe, "^(?!a[3-5])");    //drop von columns a3 - a5
// $fe = get3($fe, "^((?!dopp).)*$"); //drop columns mit dopp 
// show(flip3(get3($fe, "^c")));
function get3($fe, $v){
	$vf = vl3($fe, $v);
	$kf = array_keys($fe);
	for ($j = 0; $j < count($vf); ++$j) if (in_array($vf[$j], $kf)) $o[$vf[$j]] = $fe[$vf[$j]];
	return $o;
}

// $fe = getrnd3(10, 10, 1, 5); $fe = order3($fe, "lfn,^c[3-6]$"); show3($fe);
function old_order3($fe, $v){
	$vf = vl3($fe, $v);
	for ($j = 0; $j < count($vf); ++$j) $o[$vf[$j]] = $fe[$vf[$j]];
	while (list($k, $j) = each($fe)) if (!in_array($k, array_keys($o))) $o[$k] = $fe[$k];
	return $o;
}

// $fe = comp3($fe, "@gr@ = trunc2(@c1@ / 50);");
// $fe = comp3($fe, "@cmean@ = mean2(array(".vl3s($fe, "^c")."));");
// $fe = comp3($fe, "if (in_array(@v7@, array(0,1,2))) @v7c@ = 1; else @v7c@ = 2;");
function comp3($fe, $c){
	while (list($k, $zei) = each($fe)) $h[] = $k; reset($fe);
	preg_match_all("/(@.*?@)/", $c, $ma);
	for ($j = 0; $j < count($ma[0]); ++$j) {
		$m = $ma[0][$j]; 
		$m = preg_replace("/@/", "", $m);
		if (!in_array($m, $h)) $fe[$m] = array_fill_keys(array_keys($fe[$h[0]]), null);
		#if (!in_array($m, $h)) {echo $m; $fe[$m] = $fe[$h[0]];}
	}
	eval("while (list(\$k, \$w) = each(\$fe['$h[0]'])) ".preg_replace("/@(.*?)@/", "\$fe['\$1'][\$k]", $c));
	return $fe;
}

#show3($fe, "", "^0$:.:s_o s_u1,^last$:.:s_u1,^[2-5]$:[23]:d_r,.:.:d_u");
function show3($fe, $align = "1,2", $widths = "350,75", $borders = "^0$:.:s_o s_u,^last$:.:s_u1,[1-9]|1[0]:.:d_o", $row0insert = "", $savename = ""){
	showneu(flip3($fe), $align, $width, $borders, $row0insert, $savename); 
}

function show4($fe){
	$kf = array_keys($fe);
	$gr1 = count($fe[$kf[0]]);
	$gr2 = count($fe);
	$l = chr(10);
	
	$na = "tb_".zufallsstring(5);
	$tabname = "id = ".$na;
	$t = $l.$l."<table $tabname border = 1 style = 'font-size: 70%;' >".$l;
	$t .= "<tr>";
		for ($j = 0; $j < $gr2; ++$j) $t .= "<td>".$kf[$j]."</td>";
	$t .= "</tr>";
	
	for ($i = 0; $i < $gr1; ++$i) {
		$t .= "<tr>";
			for ($j = 0; $j < $gr2; ++$j) $t .= "<td>".$fe[$kf[$j]][$i]."</td>";
		$t .= "</tr>".$l;
	}
	$t .= "</table>".$l.$l;
	echo($t);
}

// $fe = getrnd3(499, 2, 1, 10); $fe = comp3($fe, "if (@c1@ > 9) @c1@ = '';"); $fe = comp3($fe, "if (@c2@ > 9) @c2@ = '';");
// cross3($fe, "c1", "c2", array(1,3,5), array(6,7,8));
function alt_cross3($fe, $uv1, $uv2, $schema1, $schema2){
	$uv1 = vl3($fe, $uv1)[0]; $uv2 = vl3($fe, $uv2)[0];

	$le = 9999.9999;
	$fe = comp3($fe, "if (@$uv1@ == '') @$uv1@ = $le;");
	$fe = comp3($fe, "if (@$uv2@ == '') @$uv2@ = $le;");
	
	$st1 = uvstufen3($fe, $uv1); if (is_array($schema1)) $st1 = $schema1; $gr1 = count($st1);
	$st2 = uvstufen3($fe, $uv2); if (is_array($schema2)) $st2 = $schema2; $gr2 = count($st2);
	
	for ($i = 0; $i < count($st1); ++$i){
		for ($j = 0; $j < count($st2); ++$j){
			$fe2 = filter3($fe, "@$uv1@ == '$st1[$i]' and @$uv2@ == '$st2[$j]'"); #show3($fe2);
			$nij = count2($fe2[$uv1]); if ($nij == 0) $nij = "";
			$o["r"][$i] = $st1[$i];
			$o["c".$st2[$j]][$i] = $nij;
		}
	}

	$gr = count($o["r"]);
	$o["r"][$gr] = "su";
	for ($j = 0; $j < count($st2); ++$j) {$n = "c".$st2[$j]; $o[$n][$gr] = sum2($o[$n]); }
	$o = comp3($o, "@su@ = sum2(array(".vl3s($o, "^c")."));");
	$o = comp3($o, "if (@r@ == $le) @r@ = '.';");
	$o = keyreplace3($o, "^c$le", "c.");
	show3($o);
}

#$fe = getrnd3(500, 5, 1, 10);
#for ($i = 1; $i <= 5; ++$i) $fe = comp3($fe, "@gr$i@ = is_even(@c$i@);");
#show3(means3($fe, "gr[1-2]", "lfn", "sum2,mean2"));
function means3($fe, $uv, $av, $fu = "mean2,sd2,count2"){
	$ofi = ofi(currdb(), $uv."_means3_".$av);
	$uvf = data::vl($fe, $uv); $uv0 = $uv;
	if (count($uvf) >= 1) {
		$t = "tmp_gr";
		$fe = data::comp($fe, "@$t@ = ".concat3($fe, $uv).";");
		$uv = $t;
	}
	$st = uvstufen3($fe, $uv);
	$avf = data::vl($fe, $av);
	$fuf = explode(",", $fu);
	for ($i = 0; $i < count($st); ++$i){
		$fe2 = data::filter($fe, "@$uv@ == '".$st[$i]."'");
		$o[$t][$i] = label::c($uvf[0], $st[$i]);
		for ($j = 0; $j < count($avf); ++$j){
			for ($k = 0; $k < count($fuf); ++$k){
				$v = $avf[$j]."_".$fuf[$k];
				$o[$v][$i] = call_user_func_array($fuf[$k], array($fe2[$avf[$j]]));
			}
		}
	}
	if ($uv == $t) for ($j = 0; $j < count($uvf); ++$j) $o = data::comp($o, "@".$uvf[$j]."@ = explode('.', @$t@)[".$j."];");
	$o = data::fu($o, "mean|pm|sd|median|ci", "format2(@, '0.0')");
	$o = data::get($o, $uv0.",".$avf[0]);
	$o = keyreplace3($o, $avf[0]."_", "");
	$o = labelheaders3($o);
	showneu(flip3($o));
	writexls4($o, $ofi.".xls", $cols = "18,8", $pos = "1,2", $fo = "@,0.0,0"); #showxls($ofi.".xls");
}

function wilc3($fe, $v, $fu = "mean2,sd2,min2,median2,max2,count2"){
	$avf = vl3($fe, $v);
	$fuf = explode(",", $fu);
	for ($j = 0; $j < count($avf); ++$j){
		$o["var_tmp"][$j] = $avf[$j];
		if (is_even($j)) $o["varlabel"][$j] = givelabel($avf[$j], "", 1); else $o["varlabel"][$j] = " ";
		$o["varl"][$j] = givelabel($avf[$j]);
		for ($k = 0; $k < count($fuf); ++$k){
			$n = $fuf[$k];
			$o[$n][$j] = call_user_func_array($fuf[$k], array($fe[$avf[$j]]));
		}
	}
	$o = function_on_fe3($o, "mean|sd", "format2(@, '0.00')");
	for ($j = 0; $j < count($avf); $j += 2){
		$w = r_wilc(flip3($fe), $avf[$j], $avf[$j + 1]);
		for ($k = 0; $k < count($w[0]); ++$k) $o["wilc".$k][$j] = $w[1][$k];
	}
	$o = get3($o, "^(?!(wilc[0-1]|var_tmp))");
	$o = function_on_fe3($o, "wilc[3-5]", "format2(@, '0.0')");
	$o = labelheaders3($o);
	show3($o);
	return $o;
}

//$fe = function_on_fe3($fe, "^k5$", "preg_replace('/[2-5]/', '', @)");
function function_on_fe3($fe, $v, $fu){
	$vf = vl3($fe, $v, "@");
	for ($j = 0; $j < count($vf); ++$j) $fe = comp3($fe, $vf[$j]." = ".preg_replace("/@/", $vf[$j], $fu).";");
	return $fe;
}

function vl3($fe, $v, $tx = ""){
	$vf = explode(",", $v);
	for ($j = 0; $j < count($vf); ++$j) {
		while (list($k, $zei) = each($fe)) if (preg_match("/".$vf[$j]."/", $k)) $o[] = $tx.$k.$tx;
		reset($fe);
	}
	return $o;
}

function vl3s($fe, $v, $tx = "@"){ return $tx.implode($tx.",".$tx, vl3($fe, $v)).$tx; }

// $fe = getrnd3(15, 2, 1, 10); $fe = filter3($fe, "@c1@ >= 1 and @c1@ <=3"); show(flip3($fe));
function alt_filter3($fe, $c){
	$v = "_tmp_";
	$fe = comp3($fe, "if ($c) @$v@ = 1;");
	#show(flip3($fe));
	$su = $fe[$v];
	while (list($k, $zei) = each($fe)) $na[] = $k;
	$fe = array_values($fe);

	$i2 = -1;
	for ($i = 0; $i < count($su); ++$i) if ($su[$i] == 1) {
		++$i2;
		for ($j = 0; $j < count($fe); ++$j) $o[$j][$i2] = $fe[$j][$i];
		
	}
	for ($j = 0; $j < count($o); ++$j) {$o[$na[$j]] = $o[$j]; unset($o[$j]);}
	unset($o[$v]);
	return $o;
}

//$fe = getrnd3(100, 5, 1, 10);
function getrnd3($r, $c, $lo = 0, $up = 100){
	for ($j = 1; $j <= $c; ++$j){
		for ($i = 0; $i < $r; ++$i) $o["c".$j][$i] = mt_rand($lo, $up);
	}
	return lfn3($o);
}

function cohensd($fe, $uv, $av, $fu = "mean2,sd2,count2"){
	$db = currdb();
	$st = uvstufen3($fe, $uv);
	$avf = vl3($fe, $av);
	$fuf = explode(",", $fu);
	for ($j = 0; $j < count($avf); ++$j){
		$a = $avf[$j];
		$o["av"][$j] = givelabel($a);
		for ($i = 0; $i < count($st); ++$i){
			$u = $st[$i];
			$fe2 = filter3($fe, "@$uv@ == '$u'");
			for ($k = 0; $k < count($fuf); ++$k){
				$f = $fuf[$k];
				$v = "st".$u."_".$f;
				$o[$v][$j] = call_user_func_array($f, get3($fe2, $a));
			}
		}
		$c = r_cohensd($fe, $a, $uv);
		$o["m1"][$j] = $c[1][1];
		$o["m2"][$j] = $c[1][3];
		$o["stddiff"][$j] = $c[1][7];
		$o["lo_ci"][$j] = $c[1][8];
		$o["up_ci"][$j] = $c[1][9];
	}
	$o = function_on_fe3($o, "mean|sd|median|ci", "format2(@, '0.00')");
	#show($o);
	
	$o0 = flip3($o);
	$o0[0] = preg_replace("/st([0-9])_(.+)2/", "\\2", $o0[0]);
	$o0 = labelheaders($o0);

	$cs = "colspan"; $cl = "class = sr"; $al = "align = center";
	$t = "<tr class = s_o ><td></td><td $cl $cs = 3 $al>".givelabel($uv, $st[0])."</td><td $cl $cs = 3 $al>".givelabel($uv, $st[1])."</td><td $cl $cs = 2 $al>means</td><td $cs = 3 $al>standardized difference (Cohen's D) and CI</td></tr>";
	showneu($o0, "1,3", "150,76", "^0$:.:s_o s_u1,^last$:.:s_u1,[1-9]|1[0]:.:d_o,.:^[0368]$:sr", $t); 	

	return $o;
}

function corr_rnd3($n, $c = array(.5, .4, .9), $baseprob = 0.5, $v = array("i", "d", "t")){ //3 korrelierende Binärvariablen
	$db = currdb();
	$ofi = "/tmp/corrnums.dat";
	$l = chr(10);
	$r .= "library(bindata); m <- matrix(c(1, $c[0], $c[1], $c[0], 1, $c[2], $c[1], $c[2], 1), ncol = 3); 
		x <- rmvbin($n, margprob = rep($baseprob, 3), bincorr = m);
		colnames(x) <- c('".$v[0]."', '".$v[1]."', '".$v[2]."');
		write.table(x, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); rm(list = ls());".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	$fe = read2d($ofi);
	$fe = selif3($fe, "@".$v[0]."@ !== ''");
	$fe = lfn($fe);
	return flipn($fe);
}

function corr_rnd3b($n, $cols, $corrs, $rand, $na){
	$db = currdb();
	$ofi = "/tmp/corrnums.dat";
	$l = chr(10);
	$r .= "library(bindata);
		gr = $cols; c = $corrs; r = $rand; m <- matrix(rep(c, gr * gr), ncol = gr); diag(m) <- 1;
		x <- rmvbin($n, margprob = rep(r, gr), bincorr = m);
		write.table(cor(x), file = '$ofi.corr', sep = '\\t', quote = F, row.names = F);
		write.table(x, file = '$ofi', sep = '\\t', quote = F, row.names = F);".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	$fe = read2d($ofi);
	$fe = selif3($fe, "@".$fe[0][0]."@ !== ''");
	if (is_array($na)) $fe[0] = $na;
	$fe = lfn($fe);
	
	$c = read3d($ofi.".corr");
	$c = function_on_fe3($c, "^V", "format2(@, '0.0000')");
	show3($c);
	
	return flipn($fe);
}

function corr3($fe, $av1, $av2, $me = "pearson"){
	$l = chr(10);
	$a1 = vl3($fe, $av1)[0];
	$a2 = vl3($fe, $av2)[0];
	$fe = get3($fe, "^$a1$|^$a2$");
	$ofi = "/tmp/corr.value";
	$f = "/tmp/tmp2.dat"; writefe(flip3($fe), $f, 1);
	$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric);
		c <- cor.test(x\$$a1, x\$$a2, method = '$me');
		y <- data.frame(cbind('$a1', '$a2', c\$estimate, c\$p.value, nrow(x)));
		colnames(y) <- c('corr_av1', 'corr_av2', 'corr', 'p', 'n');
	        write.table(y, file = '$ofi', sep = '\\t', quote = F, row.names = F); rm(list = ls());".$l;
	$fi = "/tmp/r2.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	$fe = read2d($ofi);
	$fe = selif3($fe, "@corr_av1@ <> ''");
	$fe = function_on_fe($fe, "^corr|^p", "format2(@, '0.0000')");
	show($fe);
}

function alt_lfn3($fe){
	$l = "lfn";
	while (list($k, $zei) = each($fe)) $h[] = $k; reset($fe);
	$fe[$l] = $fe[$h[0]];
	while (list($k, $w) = each($fe[$l])) $fe[$l][$k] = $k;
	return $fe;
}

// $fe = keyreplace3($fe, "^c1$", "z1");
// $fe = keyreplace3($fe, "^c([2-3])", "v\$1");
function keyreplace3($fe, $alt, $neu){
	return array_combine(preg_replace("/$alt/", $neu, array_keys($fe)), array_values($fe));
}

function keyneu3($fe, $alt, $neu) {
	foreach ($fe as $key => $value) if ($key == $alt) $fe2[$neu] = $value; else $fe2[$key] = $value;
	return $fe2;
}

function uvstufen3($fe, $v, $ohneleer = 1){
	$st = array_unique($fe[$v]); sort($st); 
	if ($ohneleer == 1) {for ($i = 0; $i < count($st); ++$i) if (trim($st[$i]) !== "") $o[] = $st[$i]; } else $o = $st;
	return $o;
}

function uvstufen3s($fe, $v, $tx = ",") {
	return implode($tx, uvstufen3($fe, $v));
}

function alt_merge3all($fearr, $v){
	for ($i = 0; $i < count($fearr); ++$i) {
		if ($i == 0) $fe = $fearr[0]; else $fe = merge3($fe, $fearr[$i], $v);
		#show($fe);
	}
	return $fe;
}

function alt_merge3($fe1, $fe2, $v){
	$t = "tmpkey";
	$kf1 = vl3($fe1, $v); $fe1 = comp3($fe1, "@".$t."1@ = 'k.'.@".implode("@.'.'.@", $kf1)."@;");
	$kf2 = vl3($fe2, $v); $fe2 = comp3($fe2, "@".$t."2@ = 'k.'.@".implode("@.'.'.@", $kf2)."@;");
	
	$tk0 = array_unique(array_merge($fe1[$t."1"], $fe2[$t."2"])); sort($tk); $tk0 = array_values($tk0);
	foreach ($fe1[$t."1"] as $k => $v) $tk1[$v] = $k;
	foreach ($fe2[$t."2"] as $k => $v) $tk2[$v] = $k;
	
	foreach ($tk0 as $k => $v) {
		$i0 = $k;
		$i1 = $tk1[$v];
		$i2 = $tk2[$v];
		#$fe[$t."0"][$i0] = $v;

		foreach($kf1 as $k1 => $v1) $fe[$v1][$i0] = max($fe1[$v1][$i1]."", $fe2[$v1][$i2]."");
		
		foreach ($fe1 as $k2 => $v2) if ($k2 !== $t."1" and !in_array($k2, $kf1)) $fe[$k2][$i0] = $fe1[$k2][$i1];
		foreach ($fe2 as $k2 => $v2) if ($k2 !== $t."2" and !in_array($k2, $kf2)) $fe[$k2][$i0] = $fe2[$k2][$i2];

	}
	return $fe;
}

// _____________ voll funktionierendes Beispiel ________________
// $fe1 = getrnd3(5, 5, 1, 10); $fe2 = getrnd3(5, 5, 1, 10); $fe2 = comp3($fe2, "@zz@ = 99;"); $fe2 = keyreplace3($fe2, "^c([5-9])$", "z_\\1");
// show3($fe1); show3($fe2); show3(add3all(array($fe1, $fe2)));
function add3all($fearr){ 
	for ($i = 0; $i < count($fearr); ++$i) {
		$fearr[$i] = arrayfull($fearr[$i]);
		#show3($fearr[$i]);
		if ($i == 0) $fe = $fearr[0]; else $fe = add3($fe, $fearr[$i]);
	}
	return $fe;
}

function arrayfull($fe){
	while (list($k, $w) = each($fe)) $c[] = count($fe[$k]); reset($fe);
	$mx = max2($c);
	while (list($k, $w) = each($fe)) for($i = 0; $i < $mx; ++$i) if (!isset($fe[$k][$i])) $fe[$k][$i] = "";
	return $fe;
	
}

function add3($fe1, $fe2){
	#while (list($k, $j) = each($fe1)) if (!in_array($k, array_keys($fe2))) $fe2 = comp3($fe2, "@$k@ = '';");
	#while (list($k, $j) = each($fe2)) if (!in_array($k, array_keys($fe1))) $fe1 = comp3($fe1, "@$k@ = '';");	

	while (list($k, $w) = each($fe1)) $fe12[$k] = array_merge($fe1[$k], $fe2[$k]);
	return $fe12;
}

// $fe = getrnd3(5, 2, 1, 10); show3(add3($fe, $fe));
function add3_alt($fe1, $fe2){
	while (list($k, $w) = each($fe1)) $fe12[$k] = array_merge($fe1[$k], $fe2[$k]);
	return $fe12;
}

// $fe = getrnd3(50000, 2, 1, 3); comp3($fe, "@kf@ = ".concat3($fe, ".").";"); $fe = sort3($fe, "c");
function alt_sort3($fe, $v){
	$vf = vl3($fe, $v);
	while (list($k, $w) = each($fe)) if (!in_array($k, $vf)) $vf[] = $k;
	$so = "\$fe['".implode("'],\$fe['", $vf)."']";
	eval("array_multisort($so);");
	return $fe;
}

// comp3($fe, "@kf@ = ".concat3($fe, "^c").";");
function concat3($fe, $v, $tx = "."){
	$vf = vl3($fe, $v);
	return "@".implode("@.'$tx'.@", $vf)."@";
}

// $fe = index3($fe, "^c[1-4]$");
function alt_index3($fe, $v, $o = "recnum"){
	$t = "kftmp";
	$fe = comp3($fe, "@$t@ = ".concat3($fe, $v).";");
	while (list($k, $w) = each($fe[$t])) $fe[$o][$k] = ++${$o}[$w];
	$fe = sort3($fe, $t.",".$o);
	unset($fe[$t]);
	return $fe;
}

function push3($fe, $tb){
	$k = array_keys($fe); $n = implode(",", $k);
	$fo = " text";
	$t = implode($fo.", ", $k).$fo;
	myq("drop table if exists ".$tb);
	myq("create table $tb ($t) engine = myisam");
	for ($i = 0; $i < count($fe[$k[0]]); ++$i){
		unset($r); for ($j = 0; $j < count($k); ++$j) $r[] = $fe[$k[$j]][$i];
		myq("insert into $tb ($n) values ('".implode("','", $r)."');");
	}
}

// $st = ti_st();
// $fe = comp3($fe, "if ($c) @$v@ = 1;");
// ti_en($st, "für comp ...");
function ti_st(){return microtime(true); }

function ti_en($st, $forwhat){echop($forwhat." ".format2(microtime(true) - $st, "0.000")." s"); }

function ti_en2($st){return format2(microtime(true) - $st, "0.000"); }

function writefe($fe, $fi, $gaense = 0){ //schreibt 2-dim. Feld in File
	$g = chr(34);
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	for ($i = 0; $i < $gr1; ++$i){
		unset($t);
		for ($j = 0; $j < $gr2; ++$j){
			$w = $fe[$i][$j]; $w = preg_replace("/\\n/", "", $w); $t[] = $w;
		}
		if ($gaense == 0) $s[] = implode(chr(9), $t); else $s[] = $g.implode($g.chr(9).$g, $t).$g; 
	}
	$o = implode(chr(10), $s);
	fwrite(fopen($fi, "w"), $o);
}

// $fe = getrnd3(10, 3); $fe = comp3($fe, "if (@c1@ < 50) @gruppe@ = 0; else @gruppe@ = 1;"); $fe = get3($fe, "lfn,gruppe,c[12]");
// $ofi = "/tmp/x2003.xls"; writexls3($fe, $ofi); showxls($ofi);
function writexls3($fe, $ofi, $cols = "250,80", $pos = "1,1,3,3"){
	$e = new PHPExcel();
	$s = $e->getActiveSheet();
	$kf = array_keys($fe);
	$c1 = $kf[0];
	$gr1 = count($fe[$c1]);
	$gr2 = count($kf);
	$e->getDefaultStyle()->getFont()->setName('Arial') ->setSize(12);
	$e->getDefaultStyle()->getAlignment()->setWrapText(true);
	$s->setShowGridlines(false);
	for ($i = 0; $i < $gr1; ++$i) {
		for ($j = 0; $j < $gr2; ++$j) {
			#if ($i == 0) $s->setCellValue(co($j).($i + 1), givelabel($kf[$j]));
			$a = co($j).($i + 1); 
			$w = $fe[$kf[$j]][$i];
		        if (instr($w, "<b>")) {
				$s->getStyle($a)->getFont()->setBold(true);
				$a2 = co($j).($i + 1).":".co($j + 1).($i + 1);
				if ($i > 0) $s->mergeCells($a2);
				$w = preg_replace("/\<b\>|\<\/b\>/", "", $w);
			}
		        $s->setCellValue($a, $w);
		}
		$s->setCellValue("B1", "n"); $s->setCellValue("C1", "%");
		
		$rg = co(0).($i + 1).":".co($gr2 - 1).($i + 1);
		if ($i == 0              ) $s->getStyle($rg)->applyFromArray(borderarr("top"   , "thin"));
		if ($i >= 0 and $i < $gr1) $s->getStyle($rg)->applyFromArray(borderarr("bottom", "dotted"));
		
		$s ->getRowDimension($i + 1)->setRowHeight(18);
	}
	$rg = co(0).($i + 1).":".co($gr2 - 1).($i + 1);
	$s->getStyle($rg)->applyFromArray(borderarr("bottom", "thin"));
	
	$w = PHPExcel_IOFactory::createWriter($e, 'Excel5'); $w->save($ofi); return;
	
	$a = co(0);
	$b = co($gr2 - 1);
	$rg = $a."1:".$b.($gr1 + 1);
	$c1 = $a."1:".$a.($gr1 + 1);
	$c2 = co(1)."1:".$b.($gr1 + 1);
	
	$posf = explode(",", $pos);
	for ($j = 0; $j < $gr2; ++$j) {
		if ($j < count($posf)) $po = $posf[$j]; else $po = $posf[count($posf)-1];
		$c = co($j)."1:".co($j).($gr1 + 1);
		if ($po == 1) $s->getStyle($c)->getAlignment()->setHorizontal("left");
		if ($po == 2) $s->getStyle($c)->getAlignment()->setHorizontal("center");
		if ($po == 3) $s->getStyle($c)->getAlignment()->setHorizontal("right");
	}
	
	$s->setTitle('tab1');
	$e->addNamedRange(new PHPExcel_NamedRange('tab1', $s, $rg));

	$cf = explode(",", $cols);
	for ($j = 0; $j < $gr2; ++$j) {
		if ($j < count($cf)) $wi = $cf[$j]; else $wi = $cf[count($cf)-1];
		$s->getColumnDimension(co($j))->setWidth($wi);
	}
	
	$w = PHPExcel_IOFactory::createWriter($e, 'Excel5');
	$w->save($ofi);
}

function writexls4($fe, $ofi, $cols = "80,10", $pos = "1,3,3", $fo = "@,0.0,0"){
	$e = new PHPExcel();
	$s = $e->getActiveSheet();
	$kf = array_keys($fe);
	$c1 = $kf[0];
	$gr1 = count($fe[$c1]);
	$gr2 = count($kf);
	
	$e->getDefaultStyle()->getFont()->setName('Arial') ->setSize(12);
	$e->getDefaultStyle()->getAlignment()->setWrapText(true);
	$s->setShowGridlines(false);
	for ($i = 0; $i < $gr1; ++$i) {
		for ($j = 0; $j < $gr2; ++$j) {
			if ($i == 0) $s->setCellValue(co($j).($i + 1), givelabel($kf[$j]));
			$a = co($j).($i + 2); 
			$w = $fe[$kf[$j]][$i];
			$w = strip_tags($w);
		      $s->setCellValue($a, $w);
		}
		
		$rg = co(0).($i + 1).":".co($gr2 - 1).($i + 1);
		if ($i == 0             ) $s->getStyle($rg)->applyFromArray(borderarr("top"   , "thin"  ));
		if ($i == 0             ) $s->getStyle($rg)->applyFromArray(borderarr("bottom", "thin"  ));
		if ($i > 0 and $i < $gr1) $s->getStyle($rg)->applyFromArray(borderarr("bottom", "dotted"));
		
		$s ->getRowDimension($i + 2)->setRowHeight(17);
	}
	
	$s ->getRowDimension(1)->setRowHeight(17);
	
	#$w = PHPExcel_IOFactory::createWriter($e, 'Excel5'); $w->save($ofi); return;	
	
	$rg = co(0).($i + 1).":".co($gr2 - 1).($i + 1);
	$s->getStyle($rg)->applyFromArray(borderarr("bottom", "thin"));
	
	$s->setTitle('tab1');
	$rg = "A1:".co($gr2 - 1).($i + 1);
	$e->addNamedRange(new PHPExcel_NamedRange('tab1', $s, $rg));
	
	$s->getStyle("A1:".co($gr2 - 1)."1")->getAlignment()->applyFromArray(array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,));

	$cf = explode(",", $cols);
	for ($j = 0; $j < $gr2; ++$j) {
		if ($j < count($cf)) $wi = $cf[$j]; else $wi = $cf[count($cf)-1];
		$s->getColumnDimension(co($j))->setWidth($wi);
	}
	#$s->getColumnDimension('A')->setAutoSize(true);	
	
	$posf = explode(",", $pos);
	for ($j = 0; $j < $gr2; ++$j) {
		if ($j < count($posf)) $po = $posf[$j]; else $po = $posf[count($posf)-1];
		$c = co($j)."1:".co($j).($gr1 + 1);
		if ($po == 1) $s->getStyle($c)->getAlignment()->setHorizontal("left"  );
		if ($po == 2) $s->getStyle($c)->getAlignment()->setHorizontal("center");
		if ($po == 3) $s->getStyle($c)->getAlignment()->setHorizontal("right" );
	}

	$fof = explode(",", $fo);
	for ($j = 0; $j < $gr2; ++$j) {
		if ($j < count($fof)) $f = $fof[$j]; else $f = $fof[count($fof) - 1];
		$c = co($j)."2:".co($j).($gr1 + 1);
		$s->getStyle($c)->getNumberFormat()->setFormatCode($f);
	}
	
	$w = new PHPExcel_Writer_Excel5($e);
	$w->save($ofi);
}

// $fe = data::rnd(10, 3); $fe = data::lfn($fe); writexls5($fe, "/eigenes/downs/tmp/out02.xls");
function writexls5($fe, $ofi, $cols = "80,10", $pos = "1,3,3", $fo = "@,0.0,0"){
	$fe = flip3($fe);
	
	header('Content-disposition: attachment; filename='.$ofi);
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	
	$writer = new XLSXWriter();
	$writer->writeSheet($fe,'Sheet1');
	
	#$writer = new XLSXWriter();
	#$styles1 = array( 'font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'fill'=>'#eee', 'halign'=>'center', 'border'=>'left,right,top,bottom');
	#$writer->writeSheetRow('Sheet1', $rowdata = array(300,234,456,789), $styles1 );

	$writer->writeToFile($ofi);
	exec("chmod 666 '$ofi'");
}

function writexls6_test($fe, $ofi, $cols = "80,10", $pos = "1,3,3", $fo = "@,0.0,0"){
	$spreadsheet = new Spreadsheet();
	$spreadsheet->setActiveSheetIndex(0)
	->setCellValue('A1', 'Domain')
	->setCellValue('B1', 'Category')
	->setCellValue('C1', 'Nr. Pages');

	$spreadsheet->setActiveSheetIndex(0)
	->setCellValue('A2', 'CoursesWeb.net')
	->setCellValue('B2', 'Web Development')
	->setCellValue('C2', '4000');

	$cell_st = [
	'font' =>['bold' => true],
	'alignment' =>['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
	'borders'=>['bottom' =>['style'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM]]
	];
	$spreadsheet->getActiveSheet()->getStyle('A1:C1')->applyFromArray($cell_st);

	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(16);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(18);

	$writer = new Xlsx($spreadsheet);
	$writer->save($ofi);
	exec("chmod 666 '$ofi'");
}

function writexls6_test2(){
	$spreadsheet = new Spreadsheet();
	$a = $spreadsheet->setActiveSheetIndex(0);
	$a
	->setCellValue('A1', 11)
	->setCellValue('A2', 22)
	->setCellValue('A3', 33)
	->setCellValue('B1', 44)
	->setCellValue('B2', 55)
	->setCellValue('B3', 66);

	#$a->setCellValue("A7", "= sum(a1:a3)"); //cd.xlsm!array3('test', row(), column())");
	$a->setCellValue("A7", " =cd.xlsm!SUM3(A1:B3)");
	
	$writer = new Xlsx($spreadsheet);
	$ofi = "/eigenes/downs/temp/test.xls";
	$writer->save($ofi);
	exec("chmod 666 '$ofi'");
	md("ok");
}

function co($c) {return chr(65 + $c);}

// $fe = data::rnd(10, 3); $fe = data::lfn($fe); writexls6($fe, "/eigenes/downs/test.xls");
function writexls6($fe, $ofi, $cols = "30,10", $pos = "1,1", $fo = "@,0.0,0", $append = ""){
	$spreadsheet = new Spreadsheet();
	$kf = array_keys($fe); $c1 = $kf[0]; $gr1 = count($fe[$c1]); $gr2 = count($kf);
	$spreadsheet->getDefaultStyle()->getFont()->setName('Arial') ->setSize(10);
	$spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true);
	
	$a = $spreadsheet->setActiveSheetIndex(0);
	$a->setShowGridlines(0);
	
	for ($j = 0; $j < $gr2; ++$j){
		for ($i = 0; $i < $gr1; ++$i) {
			if ($i == 0) $a->setCellValue(co($j).($i + 1), givelabel($kf[$j]));
			$w = $fe[$kf[$j]][$i];
			$a->setCellValue(chr(65 + $j).($i + 2), $w);
		}
	}
	
	if ($append !== "") {
		$g = $gr1 + 2;
		$a->setCellValue("A".$g, $append); #$a->mergeCells("A".$g.':E'.$g);
		$a->getRowDimension($g )->setRowHeight(40);
	}
	
	$r = "A1:".co($gr2 - 1).($i + 1);
	$spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('tab1', $spreadsheet->getActiveSheet(), $r));
	
	$thi = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;
	$al = new \PhpOffice\PhpSpreadsheet\Style\Alignment;
	$st = ['alignment' => ['horizontal' => $al::HORIZONTAL_RIGHT,], 'borders' => ['top' => ['borderStyle' => $thi], 'bottom' => ['borderStyle' => $thi]]];
	$a->getStyle($r)->applyFromArray($st);
	$a->getStyle('A1:'.co($gr2 - 1)."1")->getBorders()->getBottom()->setBorderStyle($thi);

	$st = ['borders' => ['bottom' => ['borderStyle' => $thi, 'color' => ['argb' => 'e5e8e8']]]];
	for ($i = 0; $i < $gr1; ++$i){
		if (preg_match('/total/', $fe["Indikator"][$i])) {
			$bo = $a->getStyle('A'.($i + 2).':'.co($gr2 - 1).($i + 2))->getBorders(); $bo->getBottom()->setBorderStyle($thi);
			$bo = $a->getStyle('A'.($i + 2).':'.co($gr2 - 1).($i + 2))->getBorders(); $bo->getTop()->setBorderStyle($thi);
		} else $a->getStyle('A'.($i + 2).':'.co($gr2 - 1).($i + 2))->applyFromArray($st);
	}
	
	for ($i = 0; $i < $gr1; ++$i){
		$w = $fe["Indikator"][$i];
		if (preg_match('/\<b\>/', $w)) {
			$r = 'A'.($i + 2);
			$a->getStyle($r)->getFont()->setBold(true);
			$a->setCellValue($r, preg_replace("/\<b\>|\<\/b\>/", "", $w));
			$a->mergeCells($r.':'.co($gr2 - 1).($i + 2));
		} 
	}
	
	$cf = explode(",", $cols);
	for ($j = 0; $j < $gr2; ++$j){
		if ($j < count($cf)) $wi = $cf[$j]; else $wi = $cf[count($cf) - 1];
		$a->getColumnDimension(co($j))->setWidth($wi);
	}
	
	
	$posf = explode(",", $pos);
	for ($j = 0; $j < $gr2; ++$j){
		if ($j < count($posf)) $po = $posf[$j]; else $po = $posf[count($posf) - 1];
		$c = co($j)."1:".co($j).($gr1 + 1);
		if ($po == 1) {$st = ['alignment' => ['horizontal' => $al::HORIZONTAL_LEFT  ]]; $a->getStyle($c)->applyFromArray($st);}
		if ($po == 2) {$st = ['alignment' => ['horizontal' => $al::HORIZONTAL_CENTER]]; $a->getStyle($c)->applyFromArray($st);}
		if ($po == 3) {$st = ['alignment' => ['horizontal' => $al::HORIZONTAL_RIGHT ]]; $a->getStyle($c)->applyFromArray($st);}
	}

	$fof = explode(",", $fo);
	for ($j = 0; $j < $gr2; ++$j){
		if ($j < count($fof)) $f = $fof[$j]; else $f = $fof[count($fof) - 1];
		$c = co($j)."2:".co($j).($gr1 + 1);
		$a->getStyle($c)->getNumberFormat()->setFormatCode($f);
	}
	
	$writer = new Xlsx($spreadsheet);
	$writer->save($ofi);
	exec("chmod 666 '$ofi'");

}

function borderarr($wo, $dicke){return array('borders' => array($wo => array('style' => $dicke,'color' => array('argb' => '000000')))); }

function get   ($q, $headers = 1){return readmysql($q, $headers = 1); }
function getmat($q, $headers = 1){return readmysql($q, $headers = 1); }

//$fe = getmat2($fe, "^(?!a[3-5])");    //drop von columns a3 - a5
//$fe = getmat2($fe, "^((?!dopp).)*$"); //drop columns mit dopp 
function getmat2($fe, $v){
	$v = implode(",", vl($fe[0], $v));
	$c = getcols($fe[0], $v); $cf = explode(",", $c);
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	for ($i = 0; $i < $gr1; ++$i){
		for ($j = 0; $j < $gr2; ++$j){
			for ($k = 0; $k < count($cf); ++$k)	if ($j == $cf[$k]) $o[$i][$k] = $fe[$i][$j];
		}
	}
	return $o;
}

//function getmysql, function getfe aus mysql
function readmysql($q, $headers = 1){  //liest mysql-tab in 2-dim Feld, Aufruf mit $fe[0][0]
	$rs = myq($q);
	if ($headers == 1) for ($j = 0; $j < mysql_num_fields($rs); ++$j) $fe[0][$j] = mysql_field_name($rs, $j);
	while($row = mysql_fetch_row($rs)) $fe[] = $row;
	return $fe;
}

//selif("lfn>10",$tb); showt($tb,1); die;
//selif("isnumeric(v4) and v4<>'' and not v4 is null",$tb);
function selif($c, $tb){ 
	if (instr($tb," where ")) $wt = " and "; else $wt = " where ";
	myq("delete from $tb $wt not($c)");
}

// between 18 and 60 = ^(1[89]|[2345]\d|60)$
// 0 or 00 bis 59: ^[0-5]?[0-9]$
// 000 bis 999: ^[0-9]{3}$
//show(selif2($fe, "^v1$", "[3-6]|9"));
function selif2($fe, $v, $preg){
	$c = getcols($fe[0], $v);
	$o[] = $fe[0];
	for ($i = 1; $i < count($fe); ++$i) if (preg_match("/$preg/", $fe[$i][$c])) $o[] = $fe[$i];
	return $o;
}

//show(selif3($fe2, "@gruppe@ == 1"));
//show(selif3($fe, "@v90@ <= 25 and inlist2(array(5,6), @v61@)"));
//show(selif3(getmat2($fe, "var,^v1$"), "preg_match('/gesa/i',@v1@) "));
function selif3($fe, $cmd){
	//echop($cmd);
	for ($j = 0; $j < count($fe[0]); ++$j) $cmd = preg_replace("/@".$fe[0][$j]."@/", "\$fe[\$i][$j]", $cmd);
	#echop($cmd);
	$o[] = $fe[0];
	for ($i = 1; $i < count($fe); ++$i) eval("if ($cmd) \$o[] = \$fe[\$i];");
	return $o;
}

// _____________ voll funktionierendes Beispiel ________________
// $fe = getrnd(2, 3); comp($fe, "@c1b@ = trunc2(@c1@ / 10);"); comp($fe, "@c1c@ = trunc2(@c2@ / 10);"); show($fe);
// show(varstocases($fe, "^c[\d]+$", "^lfn|c1[bc]$"));
function alt_varstocases($fe, $vars, $idvars, $neu = "c"){
	$vf  = vl($fe[0], $vars  ); $v  = implode(",", $vf);
	$idf = vl($fe[0], $idvars); $id = implode(",", $idf); $id0 = $id;	
	
	$c1 = getcols($fe[0], $id0); $cf1 = explode(",", $c1);
	$c2 = getcols($fe[0], $v  ); $cf2 = explode(",", $c2);
	
	for ($s = 0; $s < count($cf1); ++$s) $fe2[0][] = $idf[$s]; $fe2[0][] = "recnum"; $fe2[0][] = $neu;

	$z = 0;
	for ($i = 1; $i < count($fe); ++$i){
		for ($j = 0; $j < count($cf2); ++$j){
				++$z;
				for ($s = 0; $s < count($cf1); ++$s) $fe2[$z][] = $fe[$i][$cf1[$s]];
				$fe2[$z][] = $j + 1;
				$fe2[$z][] = $fe[$i][$cf2[$j]];
		}
	}
	
	return $fe2;
}

function flip($fe, $pre = "v"){  //transponiert $fe
	for($i = 0; $i < count($fe); ++$i){
		for($j = 0; $j < count($fe[0]); ++$j){
			$fe2[$j][$i] = $fe[$i][$j];
			if ($j == 0) $fe2[$j][$i] = $pre.$fe2[$j][$i];
		}
	}
	return $fe2;
}

function suff2($fe1,$fe2){	
	for($j=0; $j<count($fe1); $j++){
		$t .= $fe1[$j]." as ".$fe2[$j];
		if ($j<count($fe1)-1) $t .= ",";
	}
	return $t;
}

function agg2o($uv, $tb){
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";
	showq("select $uv, count($uv) as cases from $tb $wt isnull($uv) = 0 group by $uv", 1);
}

function agg3o($uv, $tb){
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";	
	showq("select $uv, count($uv) as cases from $tb $wt isnull($uv)=0 group by $uv");
}

function doubletten($fe, $v){
	$fe = index($fe, $v); show(freq($fe, "^recnum$"));
}

//function mytab
//tabelliert missing values von 3 Variablen (av in uv1 x uv2)
//                        uv2
// uv1    tot       1            2
//     n avail mis  n avail mis  n avail mis
// 1
// 2
// 3
// tot
//_______________________ Validierung __________________________
// $tb = "test"; zufallsdaten4(100, 5, $tb); lfnneu($tb);
// comp2("c1 = mysql.trunc(c1 / .50)", $tb);
// comp2("c2 = mysql.trunc(c2 / .50)", $tb);
// comp2("c1 = null", $tb." where lfn in (3,6,9,12,15) ");
// recode4("^c[3]$", $tb, array("lo - 0.2 = null"));
// show(getmat("select * from test", $tb));
// means2("c3$", "c1$", $tb);
// mistab(1, $tb, "c1$", "c2$", "c3$");
//________________________________________________________________

//mistab(1, $tb, "agek", "ge3", "v63");
function mistab($neu, $tb, $uv1, $uv2, $av){
	$db = currdb();
	$ofi = ofi($db, "mistab_".$tb."_".$uv1."_by_".$uv2);
	
	if ($neu == 1) {
		$uv1 = vl4($uv1, $tb); $uv2 = vl4($uv2, $tb); $av  = vl4($av , $tb);
		if (instr($tb," where ")) $wt = " and "; else $wt = " where ";
		$ty_uv1 = typef($uv1, $tb);
		$ty_uv2 = typef($uv2, $tb);
		$ty_av  = typef($av, $tb);

		$st = kette("select $uv2 from $tb group by $uv2");
		$stf = explode(",", $st); $stc = count($stf);
		
		for ($j = 0; $j < $stc; ++$j){
			$wh = $wt.$uv2." = '".$stf[$j]."'";
			
			if ($ty_uv1 == "text") $u1 = "$uv1 = ''"; else $u1 = "isnull($uv1)";
			if ($ty_uv2 == "text") $u2 = "$uv2 = ''"; else $u2 = "isnull($uv2)";
			if ($ty_av  == "text") $a  = "$av  = ''"; else $a  = "isnull($av )";

			if ($j == 0) {
				$fe0 = readmysql("select if($u1, '.', $uv1) as $uv1, count($uv1) as n, sum(if($a, 0, 1)) as avail, sum(if($a, 1, 0)) as mis from $tb group by $uv1");
				$gr = count($fe0); $fe0 = rowinsert($fe0, $gr);
				$fe0[$gr][0] = "total";
				$fe0[$gr][1] = sum2(vector($fe0, "n"));
				$fe0[$gr][2] = sum2(vector($fe0, "avail"));
				$fe0[$gr][3] = sum2(vector($fe0, "mis"));
				$fe0 = rowinsert($fe0, 0); $fe0[0][1] = "Gesamt";
			}
			
			$q = "select if($u1, '.', $uv1) as $uv1, count($uv1) as n, sum(if($a, 0, 1)) as avail, sum(if($a, 1, 0)) as mis from $tb $wh group by $uv1";
			$fe = readmysql($q);
			
			$gr = count($fe); $fe = rowinsert($fe, $gr);
			$fe[$gr][0] = "total";
			$fe[$gr][1] = sum2(vector($fe, "n"));
			$fe[$gr][2] = sum2(vector($fe, "avail"));
			$fe[$gr][3] = sum2(vector($fe, "mis"));
			
			$fe = rowinsert($fe, 0); $fe[0][1] = givelabel($uv2, $stf[$j]);
			
			if ($j == 0) $fe2 = merge($fe0, $fe); else $fe2 = merge($fe2, $fe);
		}
		write2(show($fe2, 0), $ofi);
	}
	$t = read2($ofi);
	echop($t);
}

//_______________ voll funktionierendes Beispiel _______________
// $fe = getrnd(10, 5); for($j = 1; $j <= 5; ++$j) comp($fe, "@c$j@ = trunc(@c$j@ / 20);");
// $fe = sort2($fe, "^c[1-2]$"); show($fe);
function alt_sort2($fe, $v, $asc = 1){ //sortiert nach mehreren Kriterien, $fe ist eine Tabelle mit Spaltenkopf
	$l = $fe[0]; $v = implode(",", vl($fe[0], $v));

	$c = getcols($fe[0], $v); $cf = explode(",", $c);
	$v = getvars($fe[0], $v); $vf = explode(",", $v);
		
	#$fe = zeileloeschen($fe, 0);
	unset($fe[0]);
	foreach ($fe as $key => $row) {
		for ($j = 0; $j < count($vf); ++$j) ${$vf[$j]}[$key] = $row[$cf[$j]];
	}
	$sortvars = "$".implode(", $", $vf);
	if ($asc == 0) $order = ", SORT_DESC";
	eval("array_multisort($sortvars $order, \$fe);");
	$fe = rowinsert($fe, 0); $fe[0] = $l;
	return $fe;
}

//$fe2 = array(array('a', 'b'), array(3,3), array(2,2), array(1,1));
//show(sortmysql($fe2, "a, b"));
function alt_sortmysql($fe, $cols){
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	$v = $fe[0];
	
	$t = "tmp_"; myq("drop table if exists ".$t);
	myq("create table $t (".implode(" text, ", $v)." text) ");
	
	for ($i = 1; $i < $gr1; ++$i) myq("insert into $t (".implode(",", $v).") values ('".implode("', '", $fe[$i])."')");
	return getmat("select * from $t order by ".$cols);
}

function alt_cross2_alt($uv1, $uv2, $fe, $neu = 1){
	$db = currdb();
	$ofi = ofi($db, "cross2_".$uv1."_by_".$uv2);
	if ($neu == 1) {
		$uv1 = vl($fe[0], $uv1)[0];
		$uv2 = vl($fe[0], $uv2)[0];
		$m = 999999;
		$fe = reco2($fe, $uv1.",".$uv2, " = $m");
		//show($fe);

		$st = uvstufen($fe, $uv2);
		$stf = explode(",", $st);
		
		$o = freq2b($fe, $uv1);
		$o = spalteloeschen($o, 1);
		
		for ($j = 0; $j < count($stf); ++$j){
			if ($j == 0) $o2 = $o;
			$u = $stf[$j];
			$se = selif3($fe, "@".$uv2."@ == $u");
			$o = freq2b($se, $uv1); $o[0][1] = $u;
			$o2 = merge($o2, $o);
		}
		
		for ($i = 0; $i < count($o2); ++$i) if ($o2[$i][0] == $m) $o2[$i][0] = ".";
		for ($j = 0; $j < count($o2[0]); ++$j) if ($o2[0][$j] == $m) $o2[0][$j] = ".";
		
		for ($i = 0; $i < count($o2); ++$i) if ($i == 0) $o2[$i][0] = givelabel($o2[$i][0]); else $o2[$i][0] = givelabel($uv1, $o2[$i][0]);
		for ($j = 1; $j < count($o2[0]); ++$j) $o2[0][$j] = givelabel($uv2, $o2[0][$j]);
		// show($o2);
		
		$f1 = "<font color = grey>"; $f2 = "</font>";
		$gr1 = count($o2); $gr2 = count($o2[0]);	
		$o2[0][$gr2] = $f1."total".$f2;
		for ($i = 1; $i < $gr1; ++$i){
			$su = 0;
			for ($j = 1; $j < $gr2; ++$j) $su += $o2[$i][$j];
			$o2[$i][$gr2] = $f1.$su.$f2;
		}

		$gr1 = count($o2); $gr2 = count($o2[0]);	
		$o2[$gr1][0] = $f1."total".$f2;
		for ($j = 1; $j < $gr2; ++$j){
			$su = 0;
			for ($i = 1; $i < $gr1; ++$i)	$su += $o2[$i][$j];
			$o2[$gr1][$j] = $f1.$su.$f2;
		}
		
		$gr1 = count($o2); $gr2 = count($o2[0]); $su = 0;
		for ($i = 1; $i < $gr1; ++$i)	$su += fromto($o2[$i][$gr2 - 1], ">", "<");
		$o2[$gr1-1][$gr2-1] = $f1.$su.$f2;
		
		$o2 = rowinsert($o2, 0); $o2[0][1] = givelabel($uv2);
		writefe($o2, $ofi);
	}
	tabnr2($ofi, givelabel($uv1)." und ".givelabel($uv2));
	$fe = read2d($ofi);
	show($fe);
}

//cross("uv1", "uv2", $fe, "1,2,3,4", "1,2,3,4");
function alt_cross($uv1, $uv2, $tb_oder_fe, $schema1 = "", $schema2 = ""){
	if (!is_array($tb_oder_fe)) {
		$tb = $tb_oder_fe;
		$uv1 = vl4($uv1, $tb); $uv2 = vl4($uv2, $tb);
		$fe = getmat("select $uv1, $uv2 from ".$tb);
	} else $fe = $tb_oder_fe;
	
	$st1 = uvstufen($fe, $uv1).",''"; if ($schema1 !== "") $st1 = $schema1; $stf1 = explode(",", $st1); $gr1 = count($stf1);
	$st2 = uvstufen($fe, $uv2).",''"; if ($schema2 !== "") $st2 = $schema2; $stf2 = explode(",", $st2); $gr2 = count($stf2);
	
	for ($i = 0; $i < $gr1; ++$i){
		for ($j = 0; $j < $gr2; ++$j){
			$nij = count(selif3($fe, "@$uv1@ == $stf1[$i] and @$uv2@ == $stf2[$j]")) - 1; if ($nij == 0) $nij = "";
			$o[$i][$j] = $nij;
			if ($i == $j) $o[$i][$j] = $nij;
		}
	}
	for ($j = 0; $j < $gr2; ++$j) $o[$gr1][$j] = count(selif3($fe, "@$uv2@ == $stf2[$j]")) - 1;
	for ($i = 0; $i < $gr1 + 1; ++$i) $o[$i][$gr2] = sum2($o[$i]);

	$o = rowinsert($o, 0);
	for ($j = 0; $j < $gr2; ++$j) {
		if ($stf2[$j] == "''") $s2 = "."; else $s2 = $stf2[$j];
		$o[0][$j] = givelabel($uv2, $s2); 
	}
	$o[0][$gr2] = "total";

	$o = colinsert($o, "aaa", 0);
	$o[0][0] = givelabel($uv1);
	for ($i = 0; $i < $gr1 + 1; ++$i) {
		if ($stf1[$i] == "''") $s1 = "."; else $s1 = $stf1[$i];
		$o[$i + 1][0] = givelabel($uv1, $s1);
		if ($o[$i][$gr2 + 1] == "") $o[$i][$gr2 + 1] = 0;
	}
	$o[$gr1 + 1][0] = "total";
	
	$o = rowinsert($o, 0);
	$o[0][1] = givelabel($uv2);
	
	$fi = ofi(currdb(), "cross_".$uv1."_".$uv2);
	writefe($o, $fi);
	show($o, "1,3", "100,50", "0,1,".$gr2);
}

//function mytab
//          uv2
//        a  b  c
// uv1 a
//     b
//     c
//uv_by_uv($fe, "agek", "ge3", 0);
function uv_by_uv($fe, $uv1, $uv2, $neu = 1){
	$db = currdb();
	$ofi = ofi($db, "uv_by_uv_".$uv1."_by_".$uv2);
	$ofi = "/eigenes/www/$db/out/uv_by_uv_".$uv1."_by_".$uv2; $ofi = preg_replace("/[^a-z0-9\\/_]/", "", $ofi);
	if ($neu == 1) {
		$uv1 = vl($fe[0], $uv1)[0];
		$uv2 = vl($fe[0], $uv2)[0];
		$m = 99;
		$fe = reco2($fe, $uv1.",".$uv2, " = $m");
		//show($fe);

		$st = uvstufen($fe, $uv2);
		$stf = explode(",", $st);
		
		$o = freq2b($fe, $uv1);
		$o = spalteloeschen($o, 1);
		
		for ($j = 0; $j < count($stf); ++$j){
			if ($j == 0) $o2 = $o;
			$u = $stf[$j];
			$se = selif3($fe, "@".$uv2."@ == $u");
			$o = freq2c($se, $uv1); $o[0][1] = $u;
			$o2 = merge($o2, $o);
		}
		
		for ($i = 0; $i < count($o2); ++$i) if ($o2[$i][0] == $m) $o2[$i][0] = ".";
		for ($j = 0; $j < count($o2[0]); ++$j) if ($o2[0][$j] == $m) $o2[0][$j] = ".";
		
		for ($i = 0; $i < count($o2); ++$i) if ($i == 0) $o2[$i][0] = givelabel($o2[$i][0]); else $o2[$i][0] = givelabel($uv1, $o2[$i][0]);
		for ($j = 1; $j < count($o2[0]); ++$j) $o2[0][$j] = givelabel($uv2, $o2[0][$j]);
		
		writefe($o2, $ofi);
	}
	$fe = read2d($ofi);
	show($fe);
}

function htestsubs($neu, $tb, $uv1, $uv2, $av){
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";

	$st = uvstufen3($uv2, $tb);
	$stf = explode(",", $st); $stc = count($stf);

	echop("<table border = 0 style = 'box-shadow: 0px 0px 0px;'><tr>");
	for ($j = 0; $j < $stc; ++$j){
		echo("<td>".givelabel($uv2, $stf[$j]));
		reptab_htest($neu, "$tb $wt $uv2 = ".$stf[$j], $uv1, $av);
		echo("</td>");
	}
	echop("</tr></table>");
}

//function mytab
function reptab_htest($neu, $tb, $uv, $av){
	$db = currdb();
	//$ofi = ofi($db, "reptab_htest_".$tb."_".$uv."_with_".$av);
	$ofi = "/eigenes/www/$db/out/reptab_htest_".$tb."_".$uv."_with_".$av; $ofi = preg_replace("/[^a-z0-9\\/_]/", "", $ofi);
	if ($neu == 1) {
		$l = chr(10);
		$r .= "con <- dbConnect(MySQL(), user='backuser', password='backuser99', dbname='$db', host='localhost');".$l;
		$r .= "x <- dbGetQuery(con, 'select $uv, $av from $tb'); x = subset(x, complete.cases(x)); x0 <- x; attach(x); x <- subset(x, select = -c($uv) ); ".$l;
		//$r .= "m <- aggregate(x, list($uv), function(x) paste(round(mean2(x),2), count2(x))  ); ".$l;
		$r .= "k <- kruskal.test($av ~ $uv, data = x); ".$l;
		$r .= "t = data.frame(c('total')); t <- cbind(t, round(k\$p.value, 5)); t <- cbind(t, count2(x)); colnames(t) <- c('test', 'pwert', 'n'); ".$l;
		$st = uvstufen3($uv, $tb);
		$stf = explode(",", $st); $stc = count($stf);
		for ($j = 0; $j < $stc; ++$j){
			for ($i = 0; $i < $stc; ++$i){
				if ($i > $j) {
					$c = "c(".$stf[$j].", ".$stf[$i].")";
					$r .= "k <- kruskal.test($av ~ $uv, data = x, subset = $uv %in% $c ); ".$l;
					$su1 = "count2(subset(x, $uv %in% ".$stf[$j]."))";
					$su2 = "count2(subset(x, $uv %in% ".$stf[$i]."))";
					$r .= "t2 = data.frame(c('".$stf[$j]." vs. ".$stf[$i]."')); t2 <- cbind(t2, round(k\$p.value, 5), paste($su1, ' : ', $su2)); colnames(t2) <- c('test', 'pwert', 'n');  ".$l;
					$r .= "t <- rbind(t, t2); ".$l;
				}
			}
		}
		$r .= "write.table(t, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); ".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi);
		exec("R --no-save --slave -q < $fi > $fi.out");	
	}
	$fe = read2d($ofi);
	$fe = selif3($fe, "is_numeric(@pwert@)");
	comp($fe, "@pwert@ = format2(@pwert@, '0.0000');");
	comp($fe, "if (@pwert@ <= 0.05) @pwert@ = '<b>'.@pwert@.'</b>';");
	show($fe);
}

function htest($fe, $uv1, $uv2){
// 	con <- dbConnect(MySQL(), user='root', password='xxx', dbname='schade_2015_02_gender', host='localhost');
// 	x <- dbGetQuery(con, 'select ge3, age from daten3b');
// 	aggregate(x, list(x$ge3), function(x) paste(round(mean2(x),2), round(sd2(x),2), count2(x))  );
// 	wilcox.test(x$age ~ x$ge3, data = x, conf.int = TRUE)
// 	wilcox.test(x$age ~ x$ge3, data = x, conf.int = TRUE)
//	kruskal.test(Ozone ~ Month, data = airquality) 
}

function chitest(){
// 	x <- dbGetQuery(con, 'select agek,v59 from daten3b');
// 	x = subset(x, complete.cases(x));
// 	chi <- chisq.test(x$agek, x$v59);
// 	table(x$agek, x$v59)
}

function chitestsubs($neu, $tb, $uv1, $uv2, $av){
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";

	$st = uvstufen3($uv2, $tb);
	$stf = explode(",", $st); $stc = count($stf);

	echop("<table border = 0 style = 'box-shadow: 0px 0px 0px;'><tr>");
	for ($j = 0; $j < $stc; ++$j){
		echo("<td>".givelabel($uv2, $stf[$j]));
		reptab_chitest($neu, "$tb $wt $uv2 = ".$stf[$j], $uv1, $av);
		echo("</td>");
	}
	echop("</tr></table>");
}

//function mytab
function reptab_chitest($neu, $tb, $uv1, $uv2){
	$db = currdb();
	
	//$ofi = ofi($db, "reptab_chitest_".$tb."_".$uv1."_with_".$uv2);
	$ofi = "/eigenes/www/$db/out/reptab_chitest_".$tb."_".$uv1."_".$uv2; $ofi = preg_replace("/[^a-z0-9\\/_]/", "", $ofi);
	
	if ($neu == 1) {
		$l = chr(10);
		$r .= "con <- dbConnect(MySQL(), user='backuser', password='backuser99', dbname='$db', host='localhost');".$l;
		$r .= "x <- dbGetQuery(con, 'select $uv1, $uv2 from $tb'); x = subset(x, complete.cases(x));".$l;
		$r .= "u1 <- data.frame(aggregate(x, list(x\$$uv1), 'count2')); ".$l;
		$r .= "u2 <- data.frame(aggregate(x, list(x\$$uv2), 'count2')); ".$l;
		$r .= "if (nrow(u1) >= 2 && nrow(u2) >=2) { chi <- chisq.test(x\$$uv1, x\$$uv2); p <- round(chi\$p.value, 5); } else p = ' - '; ".$l;
		$r .= "t = data.frame(c('total')); t <- cbind(t, p, count2(x)); colnames(t) <- c('Chi-Tests', 'pwert', 'n'); ".$l.$l;
		$st = uvstufen3($uv1, $tb);
		$stf = explode(",", $st); $stc = count($stf);
		for ($j = 0; $j < $stc; ++$j){
			for ($i = 0; $i < $stc; ++$i){
				if ($i > $j) {
					$c = "c(".$stf[$j].", ".$stf[$i].")";
					$r .= "x2 = subset(x, subset = $uv1 %in% $c); ".$l;
					
					$r .= "u1 <- data.frame(aggregate(x2, list(x2\$$uv1), 'count2')); ".$l;
					$r .= "u2 <- data.frame(aggregate(x2, list(x2\$$uv2), 'count2')); ".$l;
					$r .= "if (nrow(u1) >= 2 && nrow(u2) >=2) { chi <- chisq.test(x2\$$uv1, x2\$$uv2); p <- round(chi\$p.value, 5); } else p = ' - '; ".$l;
					
					$su1 = "count2(subset(x2, $uv1 %in% ".$stf[$j]."))";
					$su2 = "count2(subset(x2, $uv1 %in% ".$stf[$i]."))";
					$r .= "t2 = data.frame(c('".$stf[$j]." vs. ".$stf[$i]."')); t2 <- cbind(t2, p, paste($su1, ' : ', $su2)); colnames(t2) <- c('Chi-Tests', 'pwert', 'n');  ".$l;
					$r .= "t <- rbind(t, t2); ".$l.$l;
				}
			}
		}
		$r .= "write.table(t, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); ".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi);
		exec("R --no-save --slave -q < $fi > $fi.out");	
	}
	$fe = read2d($ofi);
	$fe = selif3($fe, "@pwert@ <> ''");
	comp($fe, "if (is_numeric(@pwert@)) @pwert@ = format2(@pwert@, '0.0000');");
	comp($fe, "if (@pwert@ <= 0.05) @pwert@ = '<b>'.@pwert@.'</b>';");
	show($fe);
}

//function mytab
function reptab_logrank($neu, $fe, $zeit, $event, $gruppe){ //Harrington, D. P. and Fleming, T. R. (1982). A class of rank test procedures for censored survival data. Biometrika 69, 553-566.
	$db = currdb();
	$ofi = ofi($db, $gruppe."_reptab_logrank_".$zeit."_".$event);
	$l = chr(10);
	$zeit   = vl($fe[0], $zeit  ); $zeit   = implode(",", $zeit  );
	$event  = vl($fe[0], $event ); $event  = implode(",", $event );
	$gruppe = vl($fe[0], $gruppe); $gruppe = implode(",", $gruppe);
	if ($neu == 1) {
		$fe = getmat2($fe, "^".$zeit."$,^".$event."$,^".$gruppe."$");
		$f = "/tmp/tmp.dat"; writefe($fe, $f, 1);
		$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
		$r .= "library(splines); library(survival);".$l;
		$st = uvstufen($fe, $gruppe); $stf = explode(",", $st); $stc = count($stf); $z = 0;
		for ($j = 0; $j < $stc; ++$j){
			for ($i = 0; $i < $stc; ++$i){
				if ($i > $j) {
					++$z;
					$c1 = $stf[$j]; $c2 = $stf[$i];

					$r .= "x2 = subset(x, subset = $gruppe %in% c($c1, $c2));".$l;
					$r .= "l <- survdiff(Surv($zeit, $event) ~ $gruppe, data = x2, rho = 0);".$l;  //rho 0 =  log-rank (Mantel-Haenzel), 1 = Peto & Peto modification of the Gehan-Wilcoxon test
					$r .= "p <- round(1 - pchisq(l\$chisq, length(l\$n) - 1), 4);".$l;
					$r .= "n <- l\$n;".$l;
					$r .= "hr <- (l\$obs[2] / l\$exp[2]) / (l\$obs[1] / l\$exp[1]);".$l;
					$r .= "hrl <- exp(log(hr) - qnorm(0.975) * sqrt(1 / l\$exp[2] + 1 / l\$exp[1]) );".$l;
					$r .= "hru <- exp(log(hr) + qnorm(0.975) * sqrt(1 / l\$exp[2] + 1 / l\$exp[1]) );".$l;
					$r .= "n1 <- count2(subset(x2, $gruppe %in% $c1));".$l;
					$r .= "n2 <- count2(subset(x2, $gruppe %in% $c2));".$l;

					$r .= "t <- cbind('$c1', 'vs.', '$c2', n1, n2, p, hr, hrl, hru); colnames(t) <- c('Logrank-Tests', 'vs', 'group', 'n1', 'n2', 'pwert', 'hr', 'hrl', 'hru');  ".$l;
					if ($z == 1) $r .= "t2 <- t;".$l; else $r.= "t2 <- rbind(t2, t);".$l;
				}
			}
		}
		$r .= "write.table(t2, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); rm(list = ls());".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	$fe = read2d($ofi);
	$fe = selif3($fe, "@pwert@ <> ''");
	$fe = comp($fe, "if (is_numeric(@pwert@)) @pwert@ = format2(@pwert@, '0.0000');");
	$fe = comp($fe, "if (@pwert@ < 0.0001) @pwert@ = '< 0.0001';");
	$fe = comp($fe, "if (@hru@ > 50) @hru@ = '<center> - </center>';");
	$fe = comp($fe, "if (@pwert@ <= 0.05) @pwert@ = '<b>'.@pwert@.'</b>';");
	$fe = function_on_fe($fe, "^hr", "format2(@, '0.0000')");	
	$fe = labelheaders($fe);
	$fe = labelcolumn($fe, 0, $gruppe);
	$fe = labelcolumn($fe, 2, $gruppe);
	tb("Ergebnisse des explorativen Logrank-Tests (multiple deskriptive Vergleiche), p-Werte <u><</u> 0.05 verweisen auf einen deskriptiven Unterschied.");
 	showneu($fe, "1,1,1,3,3,2", "140,30,140,80", "^0$:.:s_o s_u1,^last$:.:s_u1,[1-9]|1[0]:.:d_o", "", $ofi);
 	writexls4(flipn($fe), $ofi.".xls", $cols = "20,5,20,10,10,12,10", $pos = "1,2", $fo = "@,@,@,0,0,0.0000,0.0000,0.0000,0.0000,0,0"); #showxls($ofi.".xls");
}

// $fe = getrnd(15,3,0,9);
// $fe = walk($fe, "w_trunc2", "^c", array(5));
// meansconfig($fe, "^c[1-2]", "c3", "sum2,count2"); 
function meansconfig($fe, $uv, $av, $k = "mean2,sd2,count2", $asfe = 0){
	$uvf = vl($fe[0], $uv);
	if (count($uvf) > 1) {
		$t = "tmp_var"; 
		$uv = implode("|", $uvf); $uv0 = $uv;
		$fe = walk($fe, "w_concat", $t, array($uv, "_"));
		$uv = $t;
	}
	#show(sort2($fe, $uv.",".$av));
	
	$st = uvstufen($fe, $uv); $stf = explode(",", $st); $sgr = count($stf);
	$kf = explode(",", $k); $kgr = count($kf);
	$uvc = getcols($fe[0], $uv); $avc = getcols($fe[0], $av);
	
	for ($i = 1; $i < count($fe); ++$i) {
		for ($j = 0; $j < $sgr; ++$j) if ($fe[$i][$uvc] == $stf[$j]) $ma[$j][] = $fe[$i][$avc];
	}
	
	$o[0][0] = $t; for ($j = 0; $j < $kgr; ++$j) $o[0][$j + 1] = $kf[$j];
	for ($i = 0; $i < count($ma); ++$i){
		$o[$i + 1][0] = $stf[$i];
		for ($j = 0; $j < $kgr; ++$j) $o[$i + 1][] = call_user_func_array($kf[$j], array($ma[$i]));
	}
	
	if (count($uvf) > 1) {
		for ($j = 0; $j < count($uvf); ++$j) {			
			$u = $uvf[$j];
			$o = append($o, $u); 
			$tc = getcols($o[0], $t);
			$uc = getcols($o[0], $u);
			for ($i = 1; $i < count($o); ++$i) {
				$o[$i][$uc] = explode("_", $o[$i][$tc])[$j];
			}
		}
		$o = getmat2($o, $uv0.",".$av.",".$k);
	}
	
	
	$o = labelheaders($o);
	for ($i = 1; $i < count($o); ++$i) if ($o[$i][0] !== "") $o[$i][0] = givelabel($uv, $o[$i][0]);
	if ($asfe == 0) showneu($o); else return $o;
}

//function mytab
// ____________________ Validierungsbeispiel _____________________
//   $tb = "test"; zufallsdaten4(15, 5, $tb); lfnneu($tb);
//   comp2("c1 = mysql.trunc(c1 / .50)", $tb);
//   comp2("c1 = null", $tb." where lfn in (3,6,9,12,15) ");
//   recode4("^c[2]$", $tb, array("lo - 0.2 = null"));
//   show(getmat("select * from test", $tb));
//   means2("c2$", "c1$", $tb, "mean2,count2");
// _______________________________________________________________
//means2("var1", "gruppe$", $tb, "pm2,sum2,count2");
function means2_alt($av, $uv, $tb, $fu = "mean2,sd2,count2", $co){
	$db = currdb();
	$l = chr(10);
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";
	$na = "means2_".$av."_".$uv."_".$fu;
	$ofi = ofi($db, $na);
	
	$uv = vl4($uv, $tb); $uvf = explode(",", $uv); $uvgr = count($uvf);
	$av = vl4($av, $tb); $al = givelabel($av);
	$fuf = explode(",", $fu); $fugr = count($fuf); 
	if (instr($fu, "mean")) $te = "die Rangvarianzanalyse nach Kruskal-Wallis"; 
	if (instr($fu, "pm"  )) $te = "ein Chi²-Test";	
		
	$fs1 = "'".implode("', '", $fuf)."'";
	$fs2 = implode("($av), ", $fuf)."($av)";
		
	$r .= "con <- dbConnect(MySQL(), user = 'backuser', password = 'backuser99', dbname = '$db', host = 'localhost');
	       library(plyr); library(PropCIs);
	       x <- dbGetQuery(con, 'select $uv, $av from $tb'); x[] <- lapply(x, as.numeric);
		 #x <- subset(x, complete.cases(x));
		 y = ddply(x, ~ $uv, summarise, $fs2); 
		 colnames(y) <- c('$uv', $fs1);".$l;

	if (instr($fu, "mean")) $r .= "w   <- kruskal.test($av ~ $uv, data = x); p <- w\$p.value;".$l;
	if (instr($fu, "pm"  )) $r .= "chi <- chisq.test(x\$".$uv.", x\$".$av."); p <- chi\$p.value;".$l;
	$r .= "y\$p <- ''; y\$p [y\$$uv == min2(y[,c('$uv')])] <- p;".$l;
	$r .= "y2 <- y;".$l;
	
	$r .= "x\$alle = 9999.99; ".$l;
	$r .= "y = ddply(x, ~ x\$alle, summarise, $fs2); y\$p <- ''; colnames(y) <- c('$uv', $fs1, 'p'); ".$l;
	$r .= "y2 <- rbind(y2, y);".$l;
	
	for($j = 0; $j <= $fugr; ++$j) $g[] = "c('')"; $gs = implode(",", $g);
	
	$r .= "x <- dbGetQuery(con, \"select count(if(isnull($av), 1, 0)) as $av from $tb $wt isnull($av) or $av = '' \");".$l;
	$r .= "if (nrow(x) > 0 && x[1,1] > 0) {
			#y = cbind(c('$al missing'), $gs) ; colnames(y) <- c('$uv', $fs1, 'p');
			#y[,c('count2')] <- x[1, 1];
			#y2 <- rbind(y2, y);
			
			#x <- dbGetQuery(con, 'select count(1) from $tb');
			#y = cbind(c('n'), $gs) ; colnames(y) <- c('$uv', $fs1, 'p');
			#y[,c('count2')] <- x[1, 1];
			#y2 <- rbind(y2, y);
			
		};".$l;
	
	$r .= "write.table(y2, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); ".$l;
	$r = leadingweg($r);
// 	$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);

	$fe = read2d($ofi);
	$fe = selif3($fe, "@$uv@ <> ''");
	comp($fe, "@$uv@ = givelabel('$uv', @$uv@);");
	
 	comp($fe, "if(@$uv@ == 9999.99) @$uv@ = 'total';");
 	comp($fe, "if(@$uv@ == 'NA') @$uv@ = '.';");
 	
 	if (instr($fu, "prop.ci")) comp($fe, "\$a = explode(';', @prop.ci@); @prop@ = \$a[0]; @prop_lci@ = \$a[1]; @prop_uci@ = \$a[2];");
 	
 	comp($fe, "@p@ = format2(@p@, '0.0000');");
	comp($fe, "if (@p@ <= 0.05) @p@ = '<b>'.@p@.'</b>';");
 	
 	$fe = spalteloeschen2($fe, "prop.ci$|uv$");
 	$fe = function_on_fe($fe, "^prop" , "format2(@ * 100, '0.0')");
 	$fe = function_on_fe($fe, "^mean|^sd" , "format2(@, '0.00')");
 	$fe = function_on_fe($fe, "^pm2" , "format2(@, '0.0')");
	$fe = labelheaders($fe);
		
	tabnr2($na, "Deskriptive Statistiken: ".givelabel($av)." (n = number of cases, d.h. Stichprobengröße, p = epxlor. Signifikanztest, hier $te).");
	show($fe, "", $co);
}

function addarr($a, $b){  //einfach stapeln, prüft nicht die Spaltenordnung
	for ($i = 0; $i < count($b); ++$i) $a[] = $b[$i];
	return $a;
}

//__________________ Validierung ________________________________
// $tb = "ggg1"; zufallsdaten4(3, 5, $tb); $fe1 = getmat("select * from ".$tb); comp($fe1, "@z@ = 999;"); $fe1[0] = preg_replace("/c([45])/", "d\$1", $fe1[0]); show($fe1);
// $tb = "ggg2"; zufallsdaten4(3, 5, $tb); $fe2 = getmat("select * from ".$tb); show($fe2);
// show(addarr2($fe1, $fe2)); die;

//= function add4(){}
function addarr2($a, $b){  //prüft die Spaltenordnung
	$k1 = $a[0];
	$k2 = $b[0];
	for ($j = 0; $j < count($k1); ++$j) if (inlist3b($k, $k1[$j]) == 0) $k[] = $k1[$j];
	for ($j = 0; $j < count($k2); ++$j) if (inlist3b($k, $k2[$j]) == 0) $k[] = $k2[$j];

	$li = implode(",", $k); //br(1);
// 	echo $li." --> "; echo implode(",", $k1)." --> "; $c1 = getcols($k, implode(",", $k1)); echo $c1; $c1f = explode(",", $c1); br(1);
// 	echo $li." --> "; echo implode(",", $k2)." --> "; $c2 = getcols($k, implode(",", $k2)); echo $c2; $c2f = explode(",", $c2); br(1);

	$c1 = getcols($k, implode(",", $k1)); $c1f = explode(",", $c1);
	$c2 = getcols($k, implode(",", $k2)); $c2f = explode(",", $c2);

	$o[] = $k;

	$fe = $a; $cf = $c1f;
	$gr1 = count($fe); $gr2 = count($fe[0]); $gr3 = count($cf);
	for ($i = 1; $i < $gr1; ++$i){
		unset($t);
		for ($j = 0; $j < $gr2; ++$j) $t[$cf[$j]] = $fe[$i][$j];
		$o[] = $t;
	}	

	$fe = $b; $cf = $c2f;
	$gr1 = count($fe); $gr2 = count($fe[0]); $gr3 = count($cf);
	for ($i = 1; $i < $gr1; ++$i){
		unset($t);
		for ($j = 0; $j < $gr2; ++$j) $t[$cf[$j]] = $fe[$i][$j];
		$o[] = $t;
	}	
	return $o;
}

//_______________ Validierung 1 ________________
// $tb = "g1"; 
// zufallsdaten4(3, 5, $tb); $fe1 = getmat("select * from ".$tb); show($fe1);
// zufallsdaten4(3, 5, $tb); $fe2 = getmat("select * from ".$tb); show($fe2);
// zufallsdaten4(3, 5, $tb); $fe3 = getmat("select * from ".$tb); show($fe3);
// show(addarrays(array($fe1, $fe2, $fe3)));

//_______________ Validierung 2 ________________
// for ($j = 1; $j <= 4; ++$j) {zufallsdaten4(5,  2*$j, "z1"); $f = "fe".$j; ${$f} = getmat("select * from z1"); comp(${$f}, "@lfn@ = $j;"); show(${$f}); $fe[] = ${$f};}
// show(addarrays($fe));

function addarrays($felist){ 
	for ($i = 0; $i < count($felist); ++$i) if ($i == 0) $fe = $felist[0]; else $fe = addarr2($fe, $felist[$i]);
	return $fe;
}

//show(ordervars($fe, "lfn,c1,c2"));
function old_ordervars($fe, $v){ //sort / order vars
	$fe = getmat2($fe, $v);
	$k = $fe[0]; $c = getcols($k, $v); $cf = explode(",", $c); $vf = explode(",", $v);
	$o[] = $vf;
	$gr1 = count($fe); $gr2 = count($fe[0]);
	for ($i = 1; $i < $gr1; ++$i){
		unset($t);
		for ($j = 0; $j < $gr2; ++$j) $t[$j] = $fe[$i][$cf[$j]];
		$o[] = $t;
	}	
	return $o;
}

//_________________ Validierung ____________________
// for ($j = 1; $j <= 3; ++$j) {$f = "fe".$j; ${$f} = getrnd(5, 2); ${$f} = lfn(${$f}); ${$f}[0] = preg_replace('/^c/', "c".$j, ${$f}[0]); $fe[] = ${$f}; show(${$f});}
// show(merge($fe, "^lfn$", 0));
function alt_merge($fearr, $cols, $drop = 1){ 
	for ($i = 0; $i < count($fearr); ++$i) {
		if ($i == 0) $fe = $fearr[0]; else $fe = mergemulti_with_keys($fe, $fearr[$i], $cols, $drop);
		#show($fe);
	}
	return $fe;
}

// show(mergemulti_with_keys($fe1, $fe2, "^gruppe$,^recnum$", 1));
function alt_mergemulti_with_keys($fe1, $fe2, $cols, $drop = 0){ //zwei fe, mehrere Kriterien, sehr schnell, drop == 1 dann doppelte eliminieren
	#$time_start = microtime(true);
	$cols = implode(",", vl($fe1[0], $cols));
	
	$co1 = getcols($fe1[0], $cols); $cf1 = explode(",", $co1);
	$co2 = getcols($fe2[0], $cols); $cf2 = explode(",", $co2);
	
	foreach ($fe1 as $k => $v) {
		$key = "k";
		for($j = 0; $j < count($cf1); ++$j) $key .= ".".$v[$cf1[$j]];
		$fe1b[$key] = $v;
	}
	foreach ($fe2 as $k => $v) {
		$key = "k";
		for($j = 0; $j < count($cf2); ++$j) $key .= ".".$v[$cf2[$j]];
		$fe2b[$key] = $v;
	}
	
	$leer = $fe2[0]; for($j = 0; $j < count($leer); ++$j) $leer[$j] = "";
	
	foreach ($fe1b as $k => $v) {
		if (!is_array($fe2b[$k])) $fe2b[$k] = $leer;
		$o[$k] = array_merge($fe1b[$k], $fe2b[$k]);
	}
	$o = array_values($o);
	$o = doppelte_vars_umbenennen($o, $drop);

	//echop("merge: ".format2(microtime(true) - $time_start, "0.000")." s");
	return $o;
}

function alt_merge0($a, $b){ //zwei fe nur nach column 0
	$agr1 = count($a); $agr2 = count($a[0]);
	$bgr1 = count($b); $bgr2 = count($b[0]);
	$o = $a;
	for ($i = 0; $i < $agr1; ++$i){
		for ($j = 0; $j < $bgr1; ++$j){
			if (trim($a[$i][0]) == trim($b[$j][0])) {
				for ($k = 1; $k < $bgr2; ++$k) {
					$o[$i][$agr2 + $k - 1] = $b[$j][$k];
				}
			}
		}
	}
	return $o;
}

function showt($tb){show(getmat("select * from $tb"));}

//showtb3($tb);
function showtb3($tb){
	showq("select * from $tb", 1);
}

function showv($v, $tb, $headers = 1){	
	$v = varlist_ereg4(kontrolliere($v), $tb);
	showq("select $v from $tb", 1);
}

//zufallsdaten4(15, 1, $tb);
//comp2("c1_lag = ".lag2("c1", $tb, -1), $tb);
//showt($tb." order by lfn");

//zufallsdaten4(15, 3, $tb);
//for ($i = 1; $i <= 2; ++$i) comp2("c$i = trunc(c$i / 0.1)", $tb);
//comp2("c1_lag = ".lag2("c1", $tb." where ".lag2("c2", $tb, -1)." = c2 + 1", 0), $tb);
//showv("^c", $tb." order by lfn");
function lag2($v, $tb, $l, $o = "", $eckig = 0){
	$v = str_replace("[","",$v);
	$v = str_replace("]","",$v);
	$tb0 = preg_replace("/( where| order).+$/","", $tb);
	$tb2 = $tb0."_tmp";
	if ($o == "") $v2  = $v."_lag"; else $v2 = $o;
	myq("drop table if exists $tb2", 0);
	myq("create table $tb2 select * from $tb0 left join (select lfn-$l as lfn2,$v as $v2 from $tb) as tmp on $tb0.lfn=tmp.lfn2", 0);
	myq("drop table if exists $tb0", 0);
	myq("alter table $tb2 rename $tb0, drop column lfn2", 0);
	if ($eckig == 1) return "[$v2]"; else return $v2;
}

// $fe = getrnd(10, 3);
// for ($j = 1; $j <= 3; ++$j) comp($fe, "@c$j@ = trunc2(@c$j@ / 10);");			
/*
$tb = "tmp"; zufallsdaten4(10, 3, $tb);
for ($i = 1; $i <= 3; ++$i) comp2("c$i = mysql.trunc(c$i / 0.1)", $tb);
$fe = getmat("select * from $tb");
comp($fe, "@cc1@ = @c1@;"); $fe[0][4] = "c1";
comp($fe, "@cc2@ = @c1@;"); $fe[0][5] = "c1";
show($fe); push($fe, "tmp2"); show($fe); die;
//*/
//push($fe, "daten"); show(getmat("select * from daten"));
function push($fe, $tb){
	$fe = lfn($fe); $fo = " text ";
	$fe = doppelte_vars_umbenennen($fe);
	$t = implode($fo.",", $fe[0]).$fo;
	myq("drop table if exists ".$tb);
	myq("create table $tb ($t) engine = myisam");
	
	$v = implode(",", $fe[0]);
	for ($i = 1; $i < count($fe); ++$i){
		$fe[$i] = preg_replace("/'/", "", $fe[$i]);
		$w = "'".implode("','", $fe[$i])."'";
		if (count($fe[$i]) !== count($fe[0])) md($fe[$i][0]." f0 = ".count($fe[0])."  zei = ".count($fe[$i]) );
		myq("insert into $tb ($v) values ($w)");
	}
}

//_______________ voll funktionierendes Beispiel _______________
// $fe[0][0] =  "c1"; $fe[0][1] = "c2"; $fe[0][2] = "c3";
// $fe[1][0] =   "0"; $fe[1][1] = "11"; $fe[1][2] = "111";
// $fe[2][0] = "1.1"; $fe[2][1] = "1a"; $fe[2][2] = "a1";
// $fe[3][0] =   "3"; $fe[3][1] = "33"; $fe[3][2] = "a333";
// pushreal($fe, "daten"); show($fe); 
// show(get("select * from daten"));
function pushreal($fe, $tb){  //nur real felder in mysql
	$fe = lfn($fe);
	$fe = doppelte_vars_umbenennen($fe);
	$gr1 = count($fe); $gr2 = count($fe[0]);
	for ($i = 1; $i < $gr1; ++$i) for ($j = 0; $j < $gr2; ++$j) if (!is_numeric($fe[$i][$j])) $fe[$i][$j] = 'null';
	
	$t = implode(" real,", $fe[0])." real";
	myq("drop table if exists ".$tb);
	myq("create table $tb ($t) engine = myisam");
	
	$v = implode(",", $fe[0]);
	for ($i = 1; $i < $gr1; ++$i){
		$fe[$i] = preg_replace("/'/", "", $fe[$i]);
		$w = implode(",", $fe[$i]);
		myq("insert into $tb ($v) values ($w)");
	}
}

//drop2("^c[2-3]",$tb, $db);
//drop im fe   $fe = getmat2($fe, "^(?!a[3-5])");
function drop2($v, $tb){
	$tb = fromto($tb, "", " where ");
	$v0 = $v;
	$v = vl3($v, $tb);
	$vfe = explode(",", $v);
	if ($vfe[0] == "") return;
	for ($j = 0; $j < count($vfe); $j++) myq("alter table $tb drop column $vfe[$j]", 0);
}


//zufallsdaten4(15, 3, $tb);
//keep2("^c[2-3]", $tb);
function keep2($v, $tb){
	$v0 = $v;
	$v = vl4($v, $tb);	
	$t = $tb."temp";
	myq("drop table if exists $t");
	myq("create table $t engine = myisam select $v from $tb", 1);
	myq("drop table if exists $tb");
	myq("alter table $t rename $tb");
}

//comp2("age=trunc(vk3/10)",$tb,"real");
//$tb = $tb."where c1<110.3"; comp2("c123=c1+c2+c3",$tb); die;
//comp2("zzz7=99",$tb); die;
//comp2("c1=null",$tb); err(); die;
//comp2("rnr = rand()",$tb); //random number zwischen 0 - 1
//bmi real2("v12,v13", $tb); comp2("bmi = v12 / ( (v13/100)*(v13/100) )", $tb);
//comp2("v94n = preg_replace('/\\\D/','',v94)", $tb);
function alt_comp2($c, $tb, $ty = "real"){
	$wt = ""; $wh = "";
	if (instr($tb," where ")) $wh  = fromto($tb," where ","");
	if (instr($tb," where ")) $wt = "where";
	if ($ty == "varchar") $ty = "text";
	$tb0 = fromto($tb, "", " where ");	
	$v   = fromto($c, "", "=");
	$e = colexists2($tb0, $v);
	if ($e == false) myq("alter table $tb0 add column $v $ty");
	myq("update $tb0 set $c $wt $wh");
}

//comp($fe, "@v1@ = @v5@;");
//datum formatieren comp($fe, "\$a = explode('/', @v4@); dat1 = \$a[2].'-'.\$a[1].'-'.\$a[0];");
//comp($fe, "\$a = explode(' ', @var@); @zp@ = \$a[0]; ");
//comp($fe, "@avsum@ = sum2(array(".prae_suff($av, "@", "@")."));");
function alt_comp($fe, $cmd){
	$li1 = implode(",", $fe[0]);
	$li2 = varsincmd($cmd);
	$neu = inlist3($li1, $li2);
	$neu = doppelte_weg($neu);
	$neuf = explode(",", $neu);
	
	if (trim($neu) !== "") for ($j = 0; $j < count($neuf); ++$j) appendcol2($fe, $neuf[$j]);
	
	for ($j = 0; $j < count($fe[0]); ++$j) {
		$cmd = preg_replace("/@".$fe[0][$j]."@/i", "\$fe[\$i][$j]", $cmd);
	}

	for ($i = 1; $i < count($fe); ++$i){
		if ($cmd !== "") eval($cmd);
	}
	return $fe;
}

// $fe = getrnd(10, 5, 65, 90); $fe = walk($fe, "w_chr", "^c"); $fe = walk($fe, "w_strtolower", "^c[13]"); show($fe);
function alt_walk($fe, $function, $cols = ".", $ar) {
	$ff = vl($fe[0], $cols);
	if (count($ff) == 0) {$fe2 = append($fe, $cols); $fe = $fe2;}
	
	$cols = implode(",", vl($fe[0], $cols));
	$cf = getcols($fe[0], $cols, 1);
	
	while (list($r, $zei) = each($fe)) {
		if ($r > 0) {
			$k = setkeys($fe[0], $zei);
			while (list($c, $x) = each($zei)) if (in_array($c, $cf)) $fe[$r][$c] = call_user_func_array($function, array($x, $k, $ar));
		}
	}
	return $fe;
}

function setkeys($fe0, $zei){for ($j = 0; $j < count($zei); $j++) $neu[$fe0[$j]] = $zei[$j]; return $neu; }

function w_sum2      ($x, $zei, $ar) {$k = preg_grep("/".$ar[0]."/", array_keys($zei)); foreach ($k as $key) $o[] = $zei[$key]; return sum2($o);    }		#$fe = walk($fe, "w_sum2", "score", array("^c"));
function w_median2   ($x, $zei, $ar) {$k = preg_grep("/".$ar[0]."/", array_keys($zei)); foreach ($k as $key) $o[] = $zei[$key]; return median2($o); }
function w_mean2     ($x, $zei, $ar) {$k = preg_grep("/".$ar[0]."/", array_keys($zei)); foreach ($k as $key) $o[] = $zei[$key]; return mean2($o);   }
function w_bmi       ($x, $zei, $ar) {if ($zei[$ar[1]] !== 0) return round($zei[$ar[0]] / $zei[$ar[1]], 2); }
function w_strtolower($x, $zei, $ar) {return strtolower($x); }													#$fe = walk($fe, "w_strtolower", "^c");
function w_concat    ($x, $zei, $ar) {$k = preg_grep("/".$ar[0]."/", array_keys($zei)); foreach ($k as $key) $o[] = $zei[$key]; return implode($ar[1], $o); }	#$fe = walk($fe, "w_concat", "zz", array("^c", "-"));
function w_if        ($x, $zei, $ar) {if ($zei[$ar[0]] >= $ar[1] and $zei[$ar[0]] <= $ar[2] ) return 1; }							#$fe = walk($fe, "w_if", "zz", array("c1", 5, 15)); 
function w_if2       ($x, $zei, $ar) {if ($zei[$ar[0]] == $ar[1]) return $ar[2]; else return $zei[$ar[0]];}							#$fe = walk($fe, "w_if2", "c1", array("c1", 9, ''));
function w_addvar    ($x, $zei, $ar) {return $zei[$ar[0]]; }													#$fe = walk($fe, "w_addvar", "zz", array("c1")); 
function w_copy      ($x, $zei, $ar) {return $zei[$ar[0]]; }													#$fe = walk($fe, "w_copy", $v."b", array($v));
function w_col       ($x, $zei, $ar) {return $ar[0]; }														#$fe = walk($fe, "w_col", "alle", array(1));
function w_reco      ($x, $zei, $ar) {return preg_replace($ar[1], $ar[2], $zei[$ar[0]]); }									#$fe = walk($fe, "w_reco", "zz", array("c1", array("/^[0-5]$/","/^[6-7]$/","/^[89]$/"), array(15, 67, 88, 89)));
function w_reco2 ($x, $zei, $ar){																#$fe = walk($fe, "w_reco2", $v."b$", array("0 - 0.80 = 1", "0.81 - 1.20 = 2", "1.21 - 5 = 3"));
	for ($j = 0; $j < count($ar); ++$j) {
		$b = fromto($ar[$j], "", " - "); $e = fromto($ar[$j], " - ", ""); $o = fromto($ar[$j], " = ", "");
		if ($x !== "" and is_numeric($x)) if ($x + 0 >= $b + 0 and $x + 0 <= $e + 0) return $o;
	}
}																				#Mediansplit: $v = "v51"; $ve = vector($fe, $v); $md = median2($ve) - 0.01; $mx = max2($ve); echo $md; $fe = walk($fe, "w_copy", $v."c", array($v)); $fe = walk($fe, "w_reco2", $v."c", array("0 - $md = 0", "$md - $mx = 1"));
function w_trunc ($x, $zei, $ar){ return trunc2($zei[$ar[0]] / $ar[1]); }											#$fe = walk($fe, "w_trunc", "agek", array("age", 10));
function w_trunc2 ($x, $zei, $ar){ return trunc2($x / $ar[0]); }												#$fe = walk($fe, "w_trunc2", "^c", array(10));	
function w_switch ($x, $zei, $ar){ 																#$fe = walk($fe, "w_switch", "v4k");
	$w = $zei["v4"]; 
	if ($w < 25) return 1; if ($w >= 25 and $w < 30) return 2; if ($w >= 30) return 3;
}
function w_chr($x, $zei) {return chr($x); }															#$fe = walk($fe, "w_chr", "^c");
function w_preg ($x, $zei, $ar) {return preg_replace($ar[0], $ar[1], $x); }											#$fe = walk($fe, "w_preg", "^c", array("/[A-DQ-Z]/", "."));
function w_preg3 ($x, $zei, $ar){ if ($x < 5) $x = 5; if ($x > 8) $x = 8; return $x; }										#$fe = walk($fe, "w_preg3", "agek1");
function w_int ($x, $zei, $ar) {if ($x !== "") return intval($x); }												#$fe = walk($fe, "w_int", "v51"); 
function alt_filter($fe, $cols, $su) {																#$fe = filter($fe, "zz", "5-[1-3]");
	$cols = implode(",", vl($fe[0], $cols));
	$cf = getcols($fe[0], $cols, 1);
	$o[] = $fe[0];
	while (list($r, $zei) = each($fe)) {
		if ($r > 0) {
			$gef = 0;
 			while (list($c, $x) = each($zei)) if (in_array($c, $cf)) $gef = preg_match("/$su/", $x);
			if ($gef == 1) $o[] = $zei;
		}
	}
	return $o;
}
function w_ifboth ($x, $zei, $ar) {if ($zei[$ar[0]] == 1 and $zei[$ar[1]] == 1) return 1; else return 0; }
function w_ifone  ($x, $zei, $ar) {if ($zei[$ar[0]] == 1 or  $zei[$ar[1]] == 1) return 1; else return 0; }
//runkit_function_remove("r_if99");

function varsincmd($cmd){
	$tx = "_xx_";
	$c = preg_replace("/(@.*?@)/", "$tx\$1$tx", $cmd);
	$fe = explode($tx, $c);
	for ($j = 0; $j < count($fe); $j++){
		$v = $fe[$j];
		if (mid($v, 1, 1) == "@") $a[] = preg_replace("/@/", "", $v);
	}
	if (count($a) > 0) return implode(",", $a);	
}

function colexists2($tb, $col){
	$exists = false;   
	$rs = myq("show columns from $tb",0);	
	while($row = mysql_fetch_row($rs)) if ($row[0] == trim($col)) $exists = true;
	return $exists;
}

function newcol($tb, $v, $ty = "real null"){	
	$vfe = explode(",",$v);	
	for ($j=0; $j<count($vfe); $j++){
		$n = $vfe[$j];
		$e = colexists2($tb,$n);
		if ($e <> 1) myq("alter table $tb add column $n $ty",0);
	}
}

//showq("select '\"ab\"cde\"' as mithochkomma, preg_replace('/\"/', '', '\"ab\"cde\"') as ohnehochkomma;",$tb); die;
//comp2("geb = preg_replace('/([\\\\d]+)\\\\/([0-9]+)\\\\/([0-9]+)/','\\\\3-\\\\1-\\\\2',geburtst)",$tb,"varchar(10)");
//$z = "\\\\d+"; $sl = "\\\\/"; comp2("geb = preg_replace('/($z)$sl($z)$sl($z)/','\\\\3-\\\\1-\\\\2',geburtst)",$tb,"varchar(10)");
//backslashes $p = preg_replace('/\\\\/','\\\\\\\\',$p);
function create_replace_functions(){
	myq("use mysql;");
	$fu = array("lib_mysqludf_preg_info", "preg_capture", "preg_check", "preg_replace", "preg_rlike", "preg_position");
	for ($i = 0; $i < count($fu); ++$i) myq("drop function if exists $fu[$i];");	
	$f = "lib_mysqludf_preg.so";  // diese Datei sollte liegen unter /usr/lib/mysql/plugin/lib_mysqludf_preg.so
	myq("create function lib_mysqludf_preg_info returns string  soname '$f';");
	myq("create function preg_capture           returns string  soname '$f';");
	myq("create function preg_check             returns integer soname '$f';");
	myq("create function preg_replace           returns string  soname '$f';");
	myq("create function preg_rlike             returns integer soname '$f';");
	myq("create function preg_position          returns integer soname '$f';");
}

function preg_replace_help(){
	/* The following should be escaped if you are trying to match that character
	\ ^ . $ | ( ) [ ] * + ? { } ,

	Special Character Definitions
	\ Quote the next metacharacter
	^ Match the beginning of the line
	. Match any character (except newline)
	$ Match the end of the line (or before newline at the end)
	| Alternation
	() Grouping
	[] Character class, [^a-d] = not in a-d
	* Match 0 or more times
	+ Match 1 or more times
	? Match 1 or 0 times
	{n} Match exactly n times
	{n,} Match at least n times
	{n,m} Match at least n but not more than m times
	
	\t tab (HT, TAB)
	\n newline (LF, NL)
	\r return (CR)
	\f form feed (FF)
	\a alarm (bell) (BEL)
	\e escape (think troff) (ESC)
	\033 octal char (think of a PDP-11)
	\x1B hex char
	\c[ control char
	\l lowercase next char (think vi)
	\u uppercase next char (think vi)
	\L lowercase till \E (think vi)
	\U uppercase till \E (think vi)
	\E end case modification (think vi)
	\Q quote (disable) pattern metacharacters till \E
	\w Match a "word" character (alphanumeric plus "_")
	\W Match a non-word character
	\s Match a whitespace character
	\S Match a non-whitespace character
	\d Match a digit character
	\D Match a non-digit character
	\b Match a word boundary
	\B Match a non-(word boundary)
	\A Match only at beginning of string
	\Z Match only at end of string, or before newline at the end
	\z Match only at end of string
	\G Match only where previous m//g left off (works only with /g) */
	
	//examples
	// 0 - 750 number range  $s = "asdfasdf 3 33 333 99 750 3 33333 99 702 751 0 "; md($s); md(preg_replace("/\b([0-9]|[1-9][0-9]|[1-6][0-9]{2}|7[0-4][0-9]|750)\b/", "_", $s));  
	//ec(preg_replace("/a/","z","gabcd")); die;
	//ec(preg_replace("/a|d/","z","abcd")); die;
	//ec(preg_replace("/\s/","","a b c  d    e     f")); die;	
	//ec(preg_replace("/\s\s+/"," ","a  b  c")); die;
	//ec(preg_replace("/(\d+)\. (\w+) (\d+)/i","\$2 \$1, \$3","15. Mai 2003")); die;
	//ec(preg_replace("/^a|d$/","_","abcd")); die;
	//ec(preg_replace("/^a|[e-g]|d$/","z","abcdefghij")); die;
	//ec(preg_replace("/[^e-g]|d$/","z","abcdefghij")); die;
	//ec(preg_replace("/[^b-i]/","_","abcdefghij")); die;
	//preg_replace('/^\W+|\W+$/', '', $word) Nonzahl, Non-Buchstabe weg at begin & end of string
	//ec(preg_replace("/^\W+|\W+$/","","?!#1234abcd5678??#...?")); die;
	//ec(preg_replace("/\w+/","","?!#1234abcd5678??#*'...?")); die;	//Zahl und Buchstaben weg
	//preg_replace('/\s+/', '_', $tmp) compress internal whitespace and replace with _
	//preg_replace('/\W-/', '', $tmp) remove all non-alphanumeric chars except _ and -
	//ec(preg_replace("/\D+/","","?!#1234abcd5678??#*'...?")); die; //Zahlen bleiben
	//all to upper, Großschrift
	//    echo preg_replace('/[A-z]/e', '\'$0\' & str_pad(\'\', strlen(\'$0\'), chr(223))', "asdfasdfadfasd");
	//all to lower, Kleinschrift
	//    echo preg_replace('/[A-Z]/e', '\'$0\' | str_pad(\'\', strlen(\'$0\'), \' \')', "ASDFASDFAS");
	//Gänsefüßchen weg   showq("select '"ab"cde"' as mithochkomma, preg_replace('/\"/', '', '\"ab\"cde\"') as ohnehochkomma;"); die;
	//Gänsefüßchen weg   comp2("v11 = preg_replace('/\"/', '', v11)",$tb);
	//Umlaute weg
	//	comp2("name = preg_replace('/\"|[ÖöÜüÄäß]/', '', name)",$tb);
	//	echo preg_replace(array('/ä/','/ö/','/ü/','/ß/','/Ä/','/Ö/','/Ü/'),array('ae','oe','ue','ss','Ae','Oe','Ue'), "ÄäöÖÜü");
	//$w="ab1ba'c5d6e7'ham1ma"; pf("$w --> ".preg_replace("/'[a-z0-9]+?'/","''",$w)); die;
	//rearrange a date, Datum umordnen: comp2("datum2 = preg_replace('/(.+?)\\\/(.+?)\\\/(.+)/','\\\\2-\\\\3-\\\\1',datum1)",$tb,"varchar(10)");
	//2 arrays: echo preg_replace(array("/a/", "/b/", "/c/", "/d/"), array("A", "B", "C", "D"), "a, b, c, d");
	//alles außerhalb der Klammern weg  echo preg_replace('/^.*\((.*)\).*$/', '\$1', "abc (1) def")
	//all strings with brackets 
	//	preg_match_all("/\([^\)]*\)/", '(aa) is a (bb) string, (cc) my (dd).', $matches);
	//	echo implode("", $matches[0]);
	//strings without brackets
	//	preg_match_all("/\(([^\)]*)\)/", '(aa) is a (bb) string, (cc) my (dd).', $matches);
	//	echo implode("", $matches[1]);
	//backward / scale_y_reverse (nur Erstauthor und Jahr)
	//	$t = "Meier A, Mueller B, Schmidt C. Journal 2006;_id999.pdf";
	//	$au = fromto($t, "", " ");
	//	echo $au." ".preg_replace ("/(.+)( [0-9]{4,});(.?)(_id.+\.pdf)/", "\$2\$4", $t);
	// logical or
	//    $fe = data::filter($fe, "preg_match('/(?=.*[0-9])(?=.*\/)/', @v02@)");  #sucht Ziffer und Slash
	
}

function preg_replace_between($b, $e){
	//echop(preg_replace(array_seq(5, 15), "a", "4,5,6,10,13,15")); //ersetzt Ziffern zwischen 5 und 15
}

function array_seq($b, $e){for($i = $b; $i <= $e; ++$i) $a[] = "/\b$i\b/"; return $a;} //nur ganze Wörter daher \b und \b

function preg_replace_callback_test(){ //stellt ein Datum um
	echo preg_replace_callback('(\d{4})-(\d{2})-(\d{2})', 'mycallbackfunction', '2014-02-22');
}

function mycallbackfunction ($matches) {return $matches[3].'-'.$matches[2].'-'.$matches[1];}

//echo uvstufen($fe, $v); //läßt Missings weg
function uvstufen($fe, $v, $tx = ","){
	$fe = getmat2($fe, $v);
	for($j = 1; $j < count($fe); ++$j) if (trim($fe[$j][0]) !== "") $u[] = $fe[$j][0];
	$u = array_unique($u); sort($u);
	return implode($tx, $u);
}

//zufallsdaten4(250, 5, $tb);
//recode4("^c", $tb, array("lo - 0.49 = 0", "0.5 - hi = 1"));
//$uv = uvstufen2("c1", $tb);
//pf($uv);
function uvstufen2($v, $tb, $tx = ","){
	$rs = myq("select $v from $tb group by $v");
	while($row = mysql_fetch_row($rs)) if (trim($row[0]) !== "") $t[] = $row[0];
	return implode($tx, $t);
}

function uvstufen3alt($v, $tb){
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";
	
	$db = currdb();
	$ty = recwert("select data_type from information_schema.columns where table_schema = '$db' and table_name = '$tb' and column_name = '$v' ");
	if ($ty == "text") $wh = " trim($v) <> ''"; else $wh = " not isnull($v) ";

	$rs = myq("select $v from $tb $wt not isnull($v) and concat($v, '') <> '' group by $v");
	
	while($row = mysql_fetch_row($rs)) {$t .= $row[0].",";}
	return kommaweg($t);
}

function uvstufenlabels($v, $lb){
	$fe = explode(",", $lb);
	for($j = 0; $j < count($fe); ++$j) $fe2[$j] = givelabel($v, $fe[$j]);
	return implode(",", $fe2);
}

//how to recode
// 0.  $fe = function_on_fe3($fe, "^c[1-5]$", "preg_replace('/[2-5]/', '', @)");
// 1.  comp($fe, "if (@c1@ < 0.5) @c1@ = 0; else @c1@ = 1;");
// 2.  comp($fe, "@gr@ = preg_replace(array('/PLAC.+/', '/IMIPR.+/', '/PAROX.+/'), array(0, 1, 2), @treat@);");
// 3.  $fe = function_on_fe($fe, "^c[1-2]$", "preg_replace('/0.[0-4][0-9]/', '0', '@')");
// 4.  $v = "c1"; $o = "gr";
// 	comp($fe, "switch (1){	case (@$v@ >=  0 and @$v@ <= 30): @$o@ =  0.30; break; 
// 				case (@$v@ >  30 and @$v@ <= 50): @$o@ = 30.50; break;
// 				case (@$v@ >  50 and @$v@ <= 99): @$o@ = 50.99; break;
// 			};");
//  5. $fe = recode($fe, "^a45b$", "'^I$' = 1 | '^II$' = 2");
//  6. echop(preg_replace(array("/(\b1\b)/e", "/(\b2\b)/e", "/(\b3\b)/e"), "$1 + 10", "1, 2, 3"));
function alt_recode($fe, $v, $what){
	$r = explode(" | ", $what);
	for ($j = 0; $j < count($r); ++$j){
		$w = explode(" = ", $r[$j]);
		
		$w0 = preg_replace("/'/", "", $w[0]);
		$w1 = preg_replace("/'/", "", $w[1]);

		$fe = function_on_fe($fe, $v, "preg_replace('/$w0/', '$w1', '@')");
	}
	return $fe;
}

function alt_reco2($fe, $var, $r){
	$cols = getcols($fe[0], $var);
	$cf = explode(",", $cols);
	$rf = explode("|", $r);
	$fe2 = $fe;

	for ($i = 0; $i < count($fe); ++$i){
		for ($j = 0; $j < count($cf); ++$j){
			$co = $cf[$j];
			
			for ($r = 0; $r < count($rf); ++$r){
				$v = explode("=", $rf[$r]); $v[0] = trim($v[0]); $v[1] = trim($v[1]); if ($i == 0 and $v[0] !== "else") $vli[] = $v[0];
			
				$w = $fe[$i][$co];
				$li = explode(",", $v[0]);
				
				if (inlist2($li, $w)) $fe2[$i][$co] = $v[1];
				if ($v[0] == "else") {
					$vli2 = explode(",", implode(",", $vli));
					//echo $w." --> ".implode(",", $vli)." --> ".inlist3b($vli2, $w)." --> "; br(1);
					if (inlist3b($vli2, $w) == 0) $fe2[$i][$co] = $v[1];
				}
			}
		}
	}
	return $fe2;
}

function renvars($fe, $what, $by){
	for($j = 0; $j < count($fe[0]); ++$j) $fe[$j] = preg_replace("/$what/", $by, $fe[$j]);
	return $fe;
}

function klein($pat,$tb){ //all to lowercase, Kleinschrift
	renvars2('/[A-Z]/e','\'$0\' | str_pad(\'\', strlen(\'$0\'), \' \')',$pat,$tb);
}

function myq($q){
	global $con;
	$rs = mysqli_query($con, $q); err();
	return $rs;
}

function err(){global $con; $e = mysqli_error($con); if (trim($e) <> "") echo($e."<br>");}

//vl($fe[0], $v))
function vl($fe, $such){
	$suchf = explode(",", $such);
	for ($i = 0; $i < count($suchf); ++$i) for ($j = 0; $j < count($fe); ++$j) if (preg_match("/".$suchf[$i]."/", $fe[$j]) > 0) $o[] = $fe[$j];
	return $o;
}

function vl4($v, $tb){
	if (instr($v, " to ")) {return kontrolliere($v); }
	$vfe = explode(",", $v);
	$tb = fromto($tb, "", " where ");
	$tb = fromto($tb, "", " order ");

	$n = "column_name";
	$i = "information_schema";
	$t = "table_name";
	$db = currdb();
	
	for ($j = 0; $j < count($vfe); ++$j) {
		$te = kette("select $n from $i.columns where table_schema = '$db' and lower($t) = lower('$tb') and preg_position('/$vfe[$j]/', $n) > 0");
		if (trim($te) !== "") $kfe[] = $te;
	}
	return implode(",", $kfe);
}

//echo(doppelte_weg("a,a,b,b"));
function doppelte_weg($t){
	return implode(",",array_values(array_unique(explode(",",$t))));
}

//echo implode(",", doppelte_vars_umbenennen(array("a", "b", "b", 1, 2, 3, 4,"a")));
function doppelte_vars_umbenennen($fe, $drop = 0){
	$vars_so_far = array();
	foreach($fe[0] as $k => $var) if(in_array($var, $vars_so_far)) $fe[0][$k] .= "_dopp_".zufallsstring(3); else $vars_so_far[] = $var;
	if ($drop == 1) $fe = getmat2($fe, "^((?!dopp).)*$");  
	return $fe;
	
}

function doppelte_vars_umbenennen2($fe){
	$gr = count($fe[0]);
	for($j = 0; $j < $gr; ++$j) {$v[$j][0] = $fe[0][$j]; $v[$j][1] = $j;}
	$v = rowinsert($v, 0); $v[0][0] = "var"; $v[0][1] = "lfn";
	$v = sort2($v, "var");

	for($i = 2; $i < count($v); ++$i) {
		if ($v[$i][0] == $v[$i - 1][0]) $v[$i][0] .= "_dopp_".zufallsstring(2);
	}
	
	$v = sort2($v, "lfn");
	$v2 = vector($v, "var");
	$v2 = zeileloeschen($v2, 0);
	$fe[0] = $v2;
	return $fe;
}

create_isnumeric_function();
function create_isnumeric_function(){
	#myq("use mysql");
	myq("drop function if exists mysql.isnumeric");
	myq("create function mysql.isnumeric (sin varchar(256)) 
		returns tinyint return sin regexp '^[+-]?[0-9]*([0-9]\\\.|[0-9]|\\\.[0-9])[0-9]*(e[+-]?[0-9]+)?$';");
}

create_trunc_function();
function create_trunc_function(){
	myq("use mysql");
	myq("drop function if exists trunc");
	myq("create function trunc (myval real) returns real deterministic 
		 begin
			declare t real;
			if instr(myval,'.')>0 then
				set t = substr(myval,1,instr(myval,'.')-1);
			else
				set t = myval;
			end if;
			return t;
		 end;");		 
}

function create_max2_function(){
	myq("use mysql");
	myq("drop function if exists max2");
	myq("create function max2 (mystr varchar(10)) returns real  
		 begin
			declare t1 real;
			declare t0 real;
			set t0 = 0;
			while instr(mystr,',')>0 do
				set t1 = substr(mystr,1,instr(mystr,',')-1);
				set mystr = substr(mystr,instr(mystr,',')+1);
				if t1+0 > t0+0 then set t0 = t1; end if;
			end while;
			return t1;
		 end;");
}

/* $tb="ggg1"; zufallsdaten4(150,5,$tb);
for ($i=1;$i<=3;++$i) comp2("c$i = trunc(c$i/0.1)",$tb);
comp2("c6a = concat(',',c1,',',c2,',',c3,',')",$tb,"varchar(10)");
comp2("c6max = max2(c6a)",$tb);
comp2("c6_1 = variable(c6a,',',2)",$tb);
showt($tb." where lfn<35"); die; */
function create_variable_function(){ //split string, variable
	myq("use mysql");
	myq("drop function if exists variable");
	myq("create function variable (mystr varchar(255), tx varchar(5), pos int) returns varchar(10)
		 begin
			return replace(substring(substring_index(mystr, tx, pos),
			length(substring_index(mystr, tx, pos-1)) + 1),tx, '');
		 end;");		 
}	   

//select split_str('aa|bb|cc|dd', '|', 3) ergibt cc;
function create_split_function(){
	myq("use mysql");
	myq("drop function if exists split_str");
	myq("create function split_str(x varchar(50), delim varchar(12), pos int) returns varchar(50)
			return replace(substring(substring_index(x, delim, pos), length(substring_index(x, delim, pos -1)) + 1), delim, '');");
}

// echo liste_einfacher("c1,c2,c3,c4,c5,b10,b11,b12,e1,e2,e3");
function liste_einfacher($v){
	$l = explode(",",$v);	
	$zfe = preg_replace("/^\D+/","",$l);  //replace non-digits from start
	$tfe = preg_replace("/\d+$/","",$l);  //replace digits until string-end	
	$l2 = $l;	
	for ($i=1; $i<=count($l); ++$i){		
		if ($zfe[$i-1]+1==$zfe[$i] and $zfe[$i]+1==$zfe[$i+1] and  $tfe[$i-1]==$tfe[$i] and $tfe[$i]==$tfe[$i+1]) {
				$l2[$i]="-";
				$l2[$i+1]=$zfe[$i+1];
		}
	}	
	$t = implode(",",$l2);	
	$t = preg_replace("/,-,/","-",$t);	
	$t = preg_replace("/-{2,}/","-",$t);
	$t = preg_replace("/-,/","-",$t);		
	$t = preg_replace("/-+/","-",$t);	
	return $t;
}

function string_in_zahl($s){
	for ($i=0; $i<strlen($s); ++$i) $z .= (ord(substr($s,$i,1))-96);
	return $z;
}

function trunc2($number, $dez = 0){
	$a = explode(".", $number);
	if ($dez > 0) return $a[0].".".mid($a[1], 1, $dez); else return $a[0];
}

function trunc3($number, $dez = 0) {
	$shift = pow(10, $dez);
	return intval($number * $shift) / $shift;
}

function importcsv($f, $t, $fie){
    myq("drop table $t");

    $aufb = tablesql($fie,"varchar(10)");	
    myq("create table if not exists $t ($aufb)"); err();br(1);
    myq("load data local infile '$f' into table $t fields terminated by ',' optionally enclosed by '".chr(34)."' lines terminated by '\n' IGNORE 1 LINES ");

    for ($i=0; $i<count($fie);++$i){
        $n = $fie[$i];
        //myq("update $t set $n = replace($n,char(39),'')");
    }
}

//export2csv($fi, "select * from daten");
function export2csv($f, $sql){
	if (file_exists($f)) exec("rm $f");
	
	$rs  = myq($sql);
	$gr1 = mysqli_num_fields($rs);

	$fp = fopen($f, "a");
	for ($j = 0; $j < $gr1; ++$j){
		$l = mysqli_field_name($rs, $j);
		$l = str_replace(chr(10),"",$l);
		$l = str_replace(chr(13),"",$l);
		$l = chr(34).$l.chr(34);
		if ($j < $gr1 - 1) $l = $l.","; else $l = $l."\n";
		fwrite($fp, $l);
	}
    
	while($row = mysqli_fetch_array($rs)) {
		$l0 = $row[1];
		for ($j = 0; $j < $gr1; ++$j){
			$l = $row[$j];
			$l = str_replace(chr(8), "", $l);
			$l = str_replace(chr(10), "", $l);
			$l = str_replace(chr(13), "", $l);
			$l = str_replace(chr(15), "", $l);
			$l = str_replace(chr(34), "", $l);
			$l = str_replace(chr(39), "", $l);
			$l = str_replace(",", "", $l);
			$l = str_replace(";", "", $l);
			$l = chr(34)."".$l.chr(34);
			if ($j < $gr1 - 1) $l = $l.","; else $l = $l."\n";
			fwrite($fp, $l);
		}
	}
// 	$t = "";
// 	while($row = mysql_fetch_array($rs)) $t .= implode(chr(9), $row)."\n";
// 	fwrite($fp, $t);
	fclose($fp);
}

#$fe = getrnd3(10, 5, 1, 100); $fe = comp3($fe, "@gr@ = is_even(@lfn@);"); show3($fe);
#$e = new export; md($e->prop); $e->mysql($fe, "test22"); showt("test22");
#md(export::myage(99));
#$fi = "/tmp/test.out"; $e->asc($fe, $fi, 1); export::asc($fe, $fi, 1);
class export {

	function __construct() {
		$this->type = "text";
	}
	
	// $m = new export; $m->type = "real"; $m->mysql($fe, "datenreal");
	function mysql($fe, $tb){
		$fe = data::get($fe, ".");
		$kf = array_keys($fe);
		myq("drop table if exists ".$tb);
		myq("create table if not exists $tb (`".implode("` ".$this->type.", `", $kf)."` ".$this->type.") engine = myisam");
		
		for($i = 0; $i < count($fe[$kf[0]]); ++$i){
			unset($q);
			for($j = 0; $j < count($kf); ++$j) {
				$q[$j] = $fe[$kf[$j]][$i]; $q[$j] = preg_replace("/'/", "", $q[$j]);
				if (trim($q[$j]) == '' and $this->type == "real") $q[$j] = 'null';
			}
			$s = "insert into $tb (`".implode("`,`", $kf)."`) values ('".implode("','", $q)."');";
			$s = preg_replace("/'null'/", "null", $s);
			myq($s);
		}
	}
	
	#$m = new export; $m->mysqlreal($fe, "daten");
	function mysqlreal($fe, $tb){
		$fe = data::get($fe, ".");
		$kf = array_keys($fe);
		myq("drop table if exists ".$tb);
		myq("create table if not exists $tb (".implode(" real, ", $kf)." real) engine = myisam");
		#md(777);
		for($i = 0; $i < count($fe[$kf[0]]); ++$i){
			unset($q);
			for($j = 0; $j < count($kf); ++$j) {$q[$j] = $fe[$kf[$j]][$i]; $q[$j] = preg_replace("/'/", "", $q[$j]); if ($q[$j] == '' or !is_numeric($q[$j])) $q[$j] = 'null';}
			$s = "insert into $tb (".implode(",", $kf).") values (".implode(",", $q).");";
			#md($s);
			myq($s);
		}
	}
	
	//export::asc($fe, "/tmp/test22.asc");
	function asc($fe, $fi, $gaense = 1, $delim = ";"){
		$kf = array_keys($fe);
		$l = chr(10);
		$t = implode(chr(9), $kf).$l;
		$gaense == 1 ? $g = chr(34) : $g = "";
		
		#$u = str_repeat("_", 254);
		#for($i = 0; $i < count($fe); ++$i) $u_[] = $u;
		#$t .= implode(chr(9), $u_);
		
		for($i = 0; $i < count($fe[$kf[0]]); ++$i){
			unset($q);
			for($j = 0; $j < count($kf); ++$j) $q[] = $fe[$kf[$j]][$i]; #if (is_numeric($fe[$kf[$j]][$i])) $q[] = $fe[$kf[$j]][$i]; else $q[] = '';
			$t .= $g.implode($g.chr(9).$g, $q).$g.$l;
		}
		fwrite(fopen($fi, "w"), $t);
	}
	
	function xls($fe, $f, $cols = "5", $pos = "2", $fo = "@,0") {
		writexls4($fe, $f, $cols = "5", $pos = "2", $fo = "@,0");
	}
	
	function pushcol($tb, $fe, $col, $id){
		$z = recwert("select count(1) from information_schema.columns where table_schema = schema() and table_name = '$tb' and column_name = '$col'") + 0;
		if ($z > 0) myq("alter table $tb drop $col;");
		myq("alter table $tb add column $col text;");
		
		$i = recwert("select count(1) from information_schema.statistics where table_schema = schema() and table_name = '$tb' and index_name = '".$id."_id'") + 0;
		#if ($i > 0) myq("alter table $tb drop index ".$id."_id;");
		if ($i == 0) myq("alter table $tb add index ".$id."_id (lfn(6));");
// 		for ($i = 0; $i < count($fe[$col]); ++$i) myq("update $tb set $col = '".$fe[$col][$i]."' where $id = '".$fe["lfn"][$i]."';");
	}
}

class import {

	// $fe = import::asc($d."/dat/daten.dat");
	// $fe = data::filter($fe, "preg_match('/[0-9]/', @v01@)");
	// $fe = data::filter($fe, "trim(@SD01@) !==''");
	// $fe = data::fu($fe, "^.", "preg_replace('/-9/', '', @)");
	// $fe = data::fu($fe, ".", "preg_replace('/\"/', '', @)");
	function asc($fi){
		if (!file_exists($fi)) {md("File leider nicht gefunden...<br>$fi<br><br>...stoppe ab hier", "blue"); die; return; }
		$fe = read2d($fi);
		for ($j = 0; $j < count($fe[0]); ++$j){
			$v = trim($fe[0][$j]); $z = -1;
			for ($i = 1; $i < count($fe); ++$i) {++$z; $o[$v][$z] = $fe[$i][$j];}
		}
		$o = data::fu($o, ".", "preg_replace('/\"/', '', @)");
		$o = data::rename($o, array("/\"/"), array(""));
		return $o;
	}
	
	// $fe = import::mysql("select * from daten"); show3($fe);
	// $fe = import::mysql("select * from labels where var regexp '^k[1-9]$' "); 
	function mysql($q){
		$rs = myq($q);
		$c = mysqli_num_fields($rs);
		$z = -1;
		$finfo = mysqli_fetch_fields($rs);
		while($row = mysqli_fetch_row($rs)){
			++$z;
			for ($j = 0; $j < $c; ++$j) $fe[$finfo[$j]->name][$z] = $row[$j]."";
		}
		return $fe;
	}
	
	// $fe = import::spss($d."/dat/daten.sav");
	function spss($f){
		$r .= "
			source('/mnt/69/eigenes/commandr/functions.r'); spss('$f');
			library(foreign); 
			x <- read.spss('$f', use.value.labels = F, to.data.frame = T);
			write.table(x, file = '".$f.".asc', sep = '\\t', quote = F, row.names = F); rm(x);
			".$l;
		$fi = "/eigenes/downs/temp/r.cmd"; 
		
		#write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
		write2($r, $fi); exec("Rscript '$fi'");
		
		$fe = import::asc($f.".csv"); 
		$fe = data::rename($fe, array("/\"/"), array(""));
		return $fe;
	}
	
	function spsslabels($f){
		$r .= "
			library(foreign); 
			x <- read.spss('$f', use.value.labels = F, to.data.frame = T);
			write.table(x, file = '".$f.".asc', sep = '\\t', quote = F, row.names = F); rm(x);
			".$l;
		$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
		$fe = import::asc($f.".asc"); 
		return $fe;
	}
	
	// import::odt2mysql("/eigenes/fachinstitut/ausgaben.ods", "ausgaben 2", "datenneu");
	function odt2mysql($datafile, $sh, $mytab = "daten"){
		$l = chr(10);
		$r .= "library(readODS);".$l;
		$r .= "x <- read.ods('$datafile', sheet = '$sh', formulaAsFormula = F);".$l;
		$r .= "library(RMySQL);".$l;
		$r .= "con <- dbConnect(MySQL(), user='backuser', password='backuser99', dbname='".currdb()."', host='localhost');".$l;
		$r .= "dbWriteTable(con, '$mytab', x, row.names = 1, col.names = 1, overwrite = 1);".$l;
		$f = "/eigenes/downs/temp/r.cmd"; 
		write2($r, $f); exec("Rscript '$f'");
	}

}

class inout {

	function mysql2asc($tb, $f){
		if (file_exists($f)) exec("rm $f");
		$i = new import; $fe = $i->mysql("select * from ".$tb);
		$k = array_keys($fe); $f0 = $k[0];
		$fp = fopen($f, "a"); $t = implode(chr(9), $k).chr(10);
		for ($i = 0; $i < count($fe[$f0]); ++$i){
			$z = "";
			for ($j = 0; $j < count($k); ++$j){
				$w = $fe[$k[$j]][$i];
				$z .= $w;
				if ($j < count($k) - 1) $z .= chr(9);
			}
			$t .= $z.chr(10);
		}
		fwrite($fp, $t);
		fclose($fp);
	}
	
	function asc2mysql($f, $tb){
		$fe = read3d($f);
		$k = array_keys($fe);
		
		$b = $tb.date('Ymd_Hi', time());
		myq("drop table if exists ".$b); myq("create table $b select * from ".$tb);
		myq("drop table ".$tb); myq("create table $tb (".implode(" text, ", $k)." text) engine = myisam");
		
		for ($i = 0; $i < count($fe[$k[0]]); ++$i) {
			unset($r);
			for ($j = 0; $j < count($fe); ++$j) $r[$j] = $fe[$k[$j]][$i];
			myq("insert into $tb (".implode(", ", $k).") values('".implode("', '", $r)."')");
		}
	}
}

// $t = new table(); $t->cols = "350,100,70"; $t->reptab_freq($fe, $avlist, $uv);
class table {
	function __construct() {
		$this->cols = "300,100,70";
		$this->align = "1,1,3";
		$this->borders =  "^0$:.:s_o s_u1,^last$:.:s_u1,[1-9]|1[0]:.:d_o";
		if ($this->format == "") $this->format = "0.00";
		$this->beschr_mean = ": Mittelwerte und Standardabweichung (Werte soweit verfügbar), statistischer Test: Mann-Whitney-U-Test (Lehmann, 1998), 
					p-Werte <u><</u> 0.05 verweisen auf einen explorativ signifikanten Unterschied (Shift = Verschiebungsmaß der Gruppen gegeneinander 
					inklusive 95%-Konfidenzband der Verschiebung)."; $this->beschr_mean = "";
		$this->beschr_desc = ": Mittelwerte, Standardabweichung und weitere deskriptive Statistiken (Werte soweit verfügbar)."; $this->beschr_desc = "";
		$this->ti = new tbnr();
		$this->color = "white";
		$this->neu = true;
		$this->fo = "0.00";
		$this->step = "";
		$this->sort = "";
		$this->sortcol = "";
		$this->filter = "";
		$this->name = "";
		$this->show = true;
		$this->xls = true;
	}
	
	// $fe = data::rnd(500, 5); $fe = data::lfn($fe); $fe = data::comp($fe, "@gr1@ = trunc2(@c01@/30);"); $t->freq($fe, "gr1"); $t->reptab_means($fe, "^c0[23]$", "^gr1$", "mean2,sd2,count2");
	function reptab_means($fe, $av, $uv, $fu = "mean2,sd2,median2,count2"){
		$fe = data::get($fe, $av.",".$uv);
		$l = chr(10);
		$uv = data::vl($fe, $uv); $uvgr = count($uv);
		$av = data::vl($fe, $av); $avgr = count($av);
		$fuf = explode(",", $fu); $fugr = count($fuf);
		$ofi = ofi(currdb(), $this->name == "" ? "reptab_means_".fins($av)."_by_".fins($uv) : $this->name);
		$ti .= givelabel($av[0], "", 1).$this->beschr_mean." <font color = ".$this->color.">[".fins($av)." by ".fins($uv)."]</font>";
		
		if ($this->neu) {
			$uvst = data::uvstufen($fe, $uv[0]); $uvgr0 = count($uvst); array_unshift($uvst, implode(",", $uvst)); $uvgr = count($uvst);
			for ($s = 0; $s < count($uvst); ++$s){
				unset($o);
				$st = $uvst[$s];
				for ($i = 0; $i < $avgr; ++$i){
					$a = $av[$i];
					$u = $uv[0];
					$fe2 = data::filter($fe, "in_array(@$u@, array($st)) and @$a@ !== '' and @$u@ !=='' ");
					for ($k = 0; $k < $fugr; ++$k){
						$o["av"][$i] = $a;
						$f = $fuf[$k];
						$o[$f."_".$st][$i] = call_user_func_array($f, data::get($fe2, $a));
					}
				}
				$o = data::fu($o, "mean|sd|min|median|max", "format2(@ + 0.00001, '".$this->format."')");
				$o2[] = $o;
			}
		
			$o3 = data::merge($o2, "av");
			for ($i = 0; $i < count($o3["av"]); ++$i) {
				$a = $o3["av"][$i];
				if ($uvgr0 == 2) {
					$mw = r_mw($fe, $a, $uv[0]);
					$c = "p"       ; $o3[$c][$i] =  $mw[$c][0];
					$c = "estimate"; $o3[$c][$i] = -$mw[$c][0];
					$c = "upper"   ; $o3[$c][$i] = -$mw[$c][0];
					$c = "lower"   ; $o3[$c][$i] = -$mw[$c][0];
				} else {
					$kw = r_kw($fe, $a, $uv[0]);
					$c = "p"       ; $o3[$c][$i] =  $kw[$c][0];
				}
			}
			
			$o3 = data::fu($o3, "^estimate|^lower|^upper", "format2(@ + 0.00000001, '0.00')");
			$o3 = data::fu($o3, "^p", "format2(@ + 0.00000001, '0.0000')");
			$o3 = data::comp($o3, "@av@ = givelabel(@av@);");
			$o3 = data::comp($o3, "if (@p@ <= 0.05) @p@ = '<b>'.@p@.'</b>';");
			$o3 = data::fu($o3, "^median2_", "format2(@ + 0.00000001, '0.00')");
			
			
			//*
			$fe2 = $o3;
			$spreadsheet = new Spreadsheet();
			$kf = array_keys($fe2); $c1 = $kf[0]; $gr1 = count($fe2[$c1]); $gr2 = count($kf);
			$spreadsheet->getDefaultStyle()->getFont()->setName('Arial') ->setSize(10);
			$spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true);
			$thi = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;
			$a = $spreadsheet->setActiveSheetIndex(0);
			$a->setShowGridlines(0);
	
			for ($j = 0; $j < $gr2; ++$j){
				for ($i = 0; $i < $gr1; ++$i) {
					if ($i == 0) $a->setCellValue(co($j).($i + 2), givelabel(preg_replace(array("/_.+/"), array(""), $kf[$j])));
					$w = $fe2[$kf[$j]][$i];
					$r = chr(65 + $j).($i + 3);
					if (preg_match('/\<b\>/', $w)) {
						$a->getStyle("A".($i + 3))->getFont()->setBold(true);
						$a->getStyle($r)->getFont()->setBold(true);
					}
					$w = preg_replace("/\<b\>|\<\/b\>/", "", $w);
					$a->setCellValue($r, " ".$w);
				}
			}
			$r = "A1:".co($gr2 - 1).($i + 1);
			$spreadsheet->addNamedRange(new \PhpOffice\PhpSpreadsheet\NamedRange('tab1', $spreadsheet->getActiveSheet(), $r));
			$a->setCellValue("A2", "");
			
			$z = 2;
			for ($j = 0; $j < $uvgr; ++$j) {
				$u = $uvst[$j]; $v = $uv[0];
				$fe3 = data::filter($fe, "in_array(@$v@, array($u))"); $n = count2($fe3[$v]);
				if ($j == 0) $h = "total".chr(10)."n = ".$n; else $h = givelabel($uv[0], $uvst[$j]).chr(10)."n = ".$n;
				$r = co($z - 1).(1).":".co($z - 1 + $fugr - 1).(1);
				$a->setCellValue(chr(65 + $z - 1).(1), $h);
				$a->mergeCells($r);
				$a->getStyle($r)->getAlignment()->setHorizontal('center');
				$r = co($z - 1).(1).":".co($z - 1 + $fugr - 1).($gr1 + 2); $bo = $a->getStyle($r)->getBorders(); $bo->getLeft()->setBorderStyle($thi);
				$z = $z + $fugr;
			}
			$r = co($z - 1).(1).":".co($z - 1 + $fugr - 1).(1);
			$a->setCellValue(co($z - 1).(1), "Mann-Whitney-Test & Shift mit CI");
			$a->mergeCells($r);
			$a->getStyle($r)->getAlignment() ->setHorizontal('center') ->setVertical('center');
			$a->getRowDimension(1)->setRowHeight(25);

			$r = co($z - 1).(1).":".co($z - 1).($gr1 + 2); $bo = $a->getStyle($r)->getBorders(); $bo->getLeft()->setBorderStyle($thi);
			
			$bo = $a->getStyle('A1:'.co($z - 1 + $fugr - 1)."2")->getBorders(); $bo->getBottom()->setBorderStyle($thi);
			$bo = $a->getStyle('A1:'.co($z - 1 + $fugr - 1)."2")->getBorders(); $bo->getTop()->setBorderStyle($thi);
			
			$r = 'A'.($gr1 + 2).':'.co($z - 1 + $fugr - 1).($gr1 + 2); $bo = $a->getStyle($r)->getBorders(); $bo->getBottom()->setBorderStyle($thi);
			
			$r = "B2:".co($z - 1 + $fugr - 1).($gr1 + 2);
			$a->getStyle($r)->getAlignment()	->setHorizontal('right');
			
			for ($j = 0; $j < $gr2; ++$j) if ($j == 0) $a->getColumnDimension(co($j))->setWidth(20); else $a->getColumnDimension(co($j))->setWidth(7);
			
			$writer = new Xlsx($spreadsheet);
			$writer->save($ofi.".xls");
			exec("chmod 666 '".$ofi.".xls'");
			//*/
			
			
			
			$o3b = flip3($o3); $o3b[0] = preg_replace(array("/_.+/"), array(""), $o3b[0]); $o3c = labelheaders($o3b); $o3c = labelcolumn0($o3c); $o3c[0][0] = "";
			$c = "align = center"; $cs = "colspan = ".$fugr." class = s_r";
			for ($s = 1; $s < $uvgr; ++$s) {
				$u = $uvst[$s]; $v = $uv[0];
				$fe2 = filter3($fe, "@$v@ == $u"); $n = count2($fe2[$v]);
				$g .= "<td $c $cs>".givelabel($v, $u)."<br>n = $n</td>";
			}
			for ($i = 0; $i < $uvgr; ++$i) $z3[] = "^".(($i + 1) * $fugr)."$"; $z4 = implode("|", $z3);
			$n = count2($fe[$uv[0]]);
			if ($uvgr0 == 2) $te = "Mann-Whitney-Test & Shift mit CI"; else $te = "Rangvarianzanalyse nach Kruskal & Wallis";
			$t = "<tr class = 's_o he2'><td>".givelabel($av[0], "", 1)."</td><td $c $cs>total<br>n = $n</td>$g<td $c colspan = 4>$te</td></tr>";
			
			echop($this->ti->nr($ti, $o3)); write::fe0($o3b, $ofi);
			$s = new show();
			$s->cols = $this->cols;
			$s->firstrow = $t;
			$s->borders = "^0$:.:s_u he1,last:.:s_u,[1-9]|1[0-9]:.:d_o,.:$z4:s_r";
			$s->save = $ofi;
			$s->fe0($o3c);
			
			
			
		} else {
			$o3 = read2d($ofi);
			$o3 = flipn($o3);
			echop($this->ti->nr($ti, $o3));
			echop(read2($ofi.".html"));
		}
	}	
	
	function comment_means($o, $uv){
		$z = -1; $x = -1;
		for ($i = 0; $i < count($o["p-Wert"]); ++$i) {
			$l = $o[array_keys($o)[0]][$i];
			if ($o["p-Wert"][$i] <= 0.05) {++$z;$s[$z] = $l." mit p = <b>".$o["p-Wert"][$i]."</b>";} else {++$x; $n[$x] = $l;}
		}
		$vg = ", vergleiche jeweils die p- und die Mittelwerte in einer Zeile";
		if (is_array($n)) $ue = " In den übrigen ".count($n)." Parametern, wie ".fins($n, ", ").", waren die Gruppen hingegen nicht verschieden.";
		$r = $this->ti->ref(-1);
		if (count($s) == 0) echop("Die Gruppen unterschieden sich in keinem Parameter (".$r.$vg.").");
		if (count($s) == 1) echop("Es fand sich ein explorativ signifikanter Unterschied (".$r."): ".fins($s, ",").$vg.".".$ue);
		if (count($s) >  1) echop("Es fanden sich ".count($s)." explorativ signifikante Unterschiede (".$r."): ".fins($s, ", ").$vg.".".$ue);
	}
	
	// $fe = getrnd3(100, 5); for($i = 2; $i <= 5; ++$i) $fe = comp3($fe, "@k$i@ = trunc2(@c$i@ / 30);"); $fe = comp3($fe, "is_even(@c1@) ? @gr@ = 1 : @gr@ = 0;"); table::reptab_freq($fe, "^k[2-5]$", "^gr$");
	function reptab_freq($fe, $av, $uv, $avschema = ""){
		$fe = data::get($fe, $av.",".$uv);
		$uv = data::vl($fe, $uv); $uvgr = count($uv); 
		$av = data::vl($fe, $av); $avgr = count($av);
		$fu = "count2,percent2"; $fuf = explode(",", $fu); $fugr = count($fuf);
		$ofi = ofi(currdb(), "reptab_freq_".fins($av)." by ".fins($uv));
		$ti .= label::set($av[0]);
		
		if ($this->neu) {
			$uvst = data::uvstufen($fe, $uv[0]); array_unshift($uvst, implode(",", $uvst)); $uvgr = count($uvst);
			
			if ($avschema !== "") {$sc = explode("|", $avschema); for ($i = 0; $i < count($av); ++$i) {if (isset($sc[$i])) $sc2 = $sc[$i]; $sc3[$i] = $sc2;}}
			for ($i = 0; $i < count($av); ++$i) {$o[$i] = self::cross(data::filter($fe, "trim(@".$uv[0]."@) !== '' and trim(@".$av[$i]."@) !=='' "), $av[$i], $uv[0], $sc3[$i]);}# show3($o[$i]);}
			$o2 = data::add($o);
			$o2 = data::comp($o2, "@av_tmp_@ = label::v(@av_tmp_@);");
			$o2 = data::rename($o2, "/_tmp_(.+)/", "\\1");
			$o2 = data::rename($o2, "/av_tmp_/", label::set($av[0]));
			$o2 = data::rename($o2, "/r_tmp_/", label::v("r"));
			
			$o2b = flip3($o2);
			$o2b[0] = preg_replace(array("/^pr[0-9]?.+/", "/^c[0-9]?.+/"), array("%", "n"), $o2b[0]);
			$o2b[0][0] = ""; $o2b[0][1] = ""; $o2b = labelheaders($o2b);

			$c = "align = center"; $cs = "colspan = 2 class = 's_r cc'"; $cs0 = "colspan = 2 class = 's_r ll'";
			for ($j = 1; $j < $uvgr; ++$j) {
				$u = $uvst[$j]; $v = $uv[0];
				$fe2 = data::filter($fe, "@$v@ == $u"); $n = count2($fe2[$v]); $g .= "<td $cs>".label::c($v, $u)."<br>n = $n</td>";
			}
			for ($i = 0; $i < $avgr; ++$i) {$z += count($o[$i]["r_tmp_"]); $z2[$i] = "^".$z."$";} $z3 = implode("|", $z2);
			for ($i = 0; $i <= $uvgr; ++$i) $z4[$i] = "^".(($i * 2) + 1)."$"; $z5 = implode("|", $z4);
			$n = count2($fe[$v]);
			$t = "<tr class = 's_o'><td $cs0>".label::set($av[0])."</td><td $cs>total<br>n = $n</td>$g<td $c colspan = 4>Fisher-Test & Odds mit CI</td></tr>";
			
			$s = new show();
			$s->cols = $this->cols;
			$s->align = "1,1,3";
			$s->firstrow = $t;
			$s->borders = "^0$|$z3:.:s_u,^[1-9]$|^[1-9][0-9]$:^[1-".($uvgr * 2 + 1)."]$:d_o,.:$z5:s_r";
			
			$s->save = $ofi;
			echop($this->ti->nr($ti." <font color = ".$this->color.">[".fins($av)." by ".fins($uv)."]</font>", $o2)); write::fe($o2, $ofi);
			$s->fe0($o2b);
			
		} else {
			$o = read2d($ofi);
			$o = flipn($o);
			#echop($this->ti->nr($ti, $o));
			echop($this->ti->nr($ti." <font color = ".$this->color.">[".fins($av)." by ".fins($uv)."]</font>", $o));
			echop(read2($ofi.".html"));
		}
	}
	
	// $fe = data::rnd(50, 4, 1, 5); $fe = data::comp($fe, "is_even(@c01@) ? @gr@ = 1 : @gr@ = 0;"); show3($fe); $t->reptab_comb($fe, "^c0[2-4]$", "^gr$");
	function reptab_comb($fe, $av, $uv, $sch = "", $tests = TRUE){
		$fe = data::get($fe, $av.",".$uv);
		$l = chr(10);
		$uv = data::vl($fe, $uv); $uvgr = count($uv);
		$av = data::vl($fe, $av); $avgr = count($av); #sort($av);
		
		if ($sch == "") $sc = array("s"); else $sc = explode(",", $sch); $z = -1;
		$uvst = data::uvstufen($fe, $uv[0]); $uvgr0 = count($uvst); array_unshift($uvst, implode(",", $uvst)); $uvgr = count($uvst);
		for ($i = 0; $i < $avgr; ++$i){
			$a = $av[$i];
			$u = $uv[0];
			
			if ($z < count($sc) - 1) ++$z; else $z = 0; $sc2 = $sc[$z];
			
			for ($s = 0; $s < count($uvst); ++$s){
				$st = $uvst[$s];
				$fe2 = data::filter($fe, "in_array(@$u@, array($st)) and trim(@$a@) !== '' and @$u@ !==''");
				
				if ($i == 0) $n[$s] = count2($fe2[$u]);
				
				if ($sc2 == "s"){
					$o["av"][0] = label::v($a); $su = sum2($fe2[$a]);
					$o["k1_".$st][0] = $su;
					$o["k2_".$st][0] = "";
					$o["k3_".$st][0] = format2($su / count($fe2[$a]) * 100, "0")." %";
					$o = data::comp($o, "@u@ = '';");
				}
				if ($sc2 == "m"){
					$o["av"][0] = $a;
					$o["k1_".$st][0] = format2(mean2($fe2[$a]), "0.00");
					$o["k2_".$st][0] = "&plusmn;";
					$o["k3_".$st][0] = format2(sd2($fe2[$a]), "0.00");
					$o = data::comp($o, "@u@ = '';");
				}
				if ($sc2 == "f") {
					$o = data::freq($fe2, $a);
					$o = data::comp($o, "@av@ = label::v('$a');");
					$o = data::comp($o, "@plus@ = '';");
					$o = data::rename($o, array("/(^n$)/", "/^plus$/", "/col/"), array("k1_".$st, "k2_".$st, "k3_".$st));
					$o = data::comp($o, "if (@u@ == '') @u@ = '1';");
					
				}
				$o = data::get($o, "av,u,^k");
				$o2[] = $o; unset($o);
			}
			$o3[] = data::merge($o2, "av|u"); unset($o2);
			
			
			if ($i == 0) {md($a); show3(r_kw($fe, $a, $u));}
		}
		$o4 = data::add($o3);

		
		
		for ($i = count($o4["av"]); $i > 1; --$i) if ($o4["av"][$i] == $o4["av"][$i - 1]) $o4["av"][$i] = "";
		$o4 = data::comp($o4, "if (@av@ !== '') @av@ = label::v(@av@);");
		
		
		
		$sty = "style = 'text-align:center'";
		for ($j = 1; $j < count($uvst); ++$j) $t .= "<td class = s_l colspan = 3 $sty >".label::c($u, $uvst[$j])."<br>n = ".$n[$j]."</td>";
		$fi = "<tr><td >".label::set($av[0])."</td><td></td><td class = s_l $sty colspan = 3>total<br>n = ".$n[0]."</td>".$t."</tr>";
		
		$o5 = flip3($o4); unset($o5[0]); $o5 = array_values($o5);
		
		$s = new show();
		$s->firstrow = $fi;
		$s->borders = "^0$:.:s_o,lastbut1:.:s_u,.:^[147]$|^1[0]$:s_r,[0-9]:.:d_u";
		$s->cols = "360,15,60,15,60,60,15,60,60,15,60";
		
		$s->fe0($o5);
	}
	
	function reptab_freq_alt($fe, $av, $uv, $avschema = "", $comm = 1){
		$l = chr(10);
		$fe = get3($fe, $av.",".$uv);
		$uv = data::vl($fe, $uv); $uvgr = count($uv); $uvst = data::uvstufen($fe, $uv[0]); array_unshift($uvst, implode(",", $uvst)); $uvgr = count($uvst);
		$av = data::vl($fe, $av); $avgr = count($av);
		$fu = "count2,percent2"; $fuf = explode(",", $fu); $fugr = count($fuf);
		
		if ($avschema !== "") {$sc = explode("|", $avschema); for ($i = 0; $i < count($av); ++$i) {if (isset($sc[$i])) $sc2 = $sc[$i]; $sc3[$i] = $sc2;}}
		
		for ($i = 0; $i < count($av); ++$i) {$o[$i] = self::cross(data::filter($fe, "trim(@".$uv[0]."@) !== '' and trim(@".$av[$i]."@) !=='' "), $av[$i], $uv[0], $sc3[$i]);}# show3($o[$i]);}
		$o2 = data::add($o);
		$o2 = data::comp($o2, "@av_tmp_@ = label::v(@av_tmp_@);");
		$o3 = flip3($o2);
		$o3[0] = preg_replace(array("/c_tmp_(.+)/", "/pr_tmp_(.+)/", "/(r|av)_tmp_/"), array("n", "%", ""), $o3[0]);
		$o3 = labelheaders($o3);

		$c = "align = center"; $cs = "colspan = 2 class = 's_r cc'";
		for ($j = 1; $j < $uvgr; ++$j) {
			$u = $uvst[$j]; $v = $uv[0];
			$fe2 = data::filter($fe, "@$v@ == $u"); $n = count2($fe2[$v]); $g .= "<td $cs>".label::c($v, $u)."<br>n = $n</td>";
		}
		for ($i = 0; $i < $avgr; ++$i) {$z += count($o[$i]["r_tmp_"]); $z2[$i] = "^".$z."$";} $z3 = implode("|", $z2);
		for ($i = 0; $i <= $uvgr; ++$i) $z4[$i] = "^".(($i * 2) + 1)."$"; $z5 = implode("|", $z4);
		$n = count2($fe[$v]);
		
		#$ti .= label::set($av[0]).": Häufigkeiten und Unterschiedsprüfung (n = number of cases, p = p-Wert zur Frage, ob sich die Gruppen unterscheiden, CI = confidence interval), 
		#	statistischer Test: Fisher-Test, p-Werte <u><</u> 0.05 verweisen auf einen explorativ signifikanten Unterschied, Odds-Ratio = Risikomaß inklusive der 95%-Vertrauensbereiche.";

		$ti .= label::set($av[0]);
		echop($this->ti->nr($ti." <font color = ".$this->color.">[".fins($av)." by ".fins($uv)."]</font>", $o2));
		
		$t = "<tr class = 's_o'><td $cs>".label::set($av[0])."</td><td $cs>total<br>n = $n</td>$g<td $c colspan = 4>Fisher-Test & Odds mit CI</td></tr>";
		showneu($o3, $this->align, $this->cols, "^0$|$z3:.:s_u,^[1-9]$|^[1-9][0-9]$:^[1-".($uvgr * 2 + 1)."]$:d_o,.:$z5:s_r", $t);
		
		#if ($comm == 1) self::comment_freq($o2, $uv[0], $uvst);
	}	
	
	function comment_freq($o, $uv){
		$z = -1; $x = -1;
		for ($i = 0; $i < count($o["p-Wert"]); ++$i) {
			$p = $o["p-Wert"][$i]; $l = $o["av_tmp_"][$i];
			if ($p !== '') {
				if ($p <= 0.05) {
					$n1[++$z] = $l;
					$s[$z] = $l." mit p = ".$p;
					$w1[$z] = $o["pr_tmp_1"][$i + 1]." vs ".$o["pr_tmp_0"][$i + 1];
				} else {$n2[++$x] = $l;}
			}
		}
		$r = $this->ti->ref(-1);
		#if (count($s) == 0) echop("Die Gruppen unterschieden sich in keinem Parameter der ".tbref(-1).".");
		if (count($s) == 0) echop("Die Gruppen unterschieden sich in keinem Parameter der $r.");
		if (count($s) == 1) echop("Die $r zeigt einen explorativ signifikanten Unterschied: ".fins($s, ", ").".");
		if (count($s) >  1) echop("Die $r zeigt mehrere explorativ signifikante Unterschiede (".fins($n1, ", ")."): ".fins($s, ", ").".");
	}
	
	// $fe = getrnd3(100, 2); $fe = comp3($fe, "is_even(@c1@) ? @gr1@ = 1 : @gr1@ = 0;"); $fe = comp3($fe, "is_even(@c2@) ? @gr2@ = 1 : @gr2@ = 0;"); show3(table::cross($fe, "gr1", "gr2"));*/
	function cross($fe, $av, $uv, $avschema = ""){
		$fe = data::get($fe, $av.",".$uv);
		
		if ($avschema == "") $st1 = data::uvstufen($fe, $av); else $st1 = explode(",", kontrolliere($avschema));
		if ($uvschema == "") $st2 = data::uvstufen($fe, $uv); else $st2 = explode(",", kontrolliere($uvschema));
		
		#show3($fe);
		for ($i = 0; $i < count($st1); ++$i){
			$o["av_tmp_"][$i] = "";
			$o["r_tmp_"] [$i] = "";
			$o["tot"]    [$i] = "";
			$o["totproz"][$i] = "";
			for ($j = 0; $j < count($st2); ++$j){
				$u1 = $st1[$i]; $u2 = $st2[$j];
				
				$su = data::filter($fe, "@$uv@ == '$u2' and @$av@ !== ''");
				$fe2 = data::filter($fe, "@$av@ == $u1 and @$uv@ == '$u2'");
				$nij = count2($fe2[$uv]); if ($nij == 0) $nij = "";
				#md($u1." ".$u2." ".$nij);
				
				if ($i == 0) $o["av_tmp_"][$i] = $av;
				$o["r_tmp_"][$i] = label::c($av, $u1);
				$o["c_tmp_".$u2][$i] = $nij;
				$o["pr_tmp_".$st2[$j]][$i] = number_format($nij / count2($su[$uv]) * 100, 1);
				$c_p = "c_tmp_".$u2.","."pr_tmp_".$u2.",";
			}
			for ($j = 0; $j < count($st2); ++$j) $o["tot"][$i] += $o["c_tmp_".$st2[$j]][$i];
			$su = data::filter($fe, "@$av@ !== ''");
			for ($j = 0; $j < count($st2); ++$j) $o["totproz"][$i] = number_format($o["tot"][$i] / count2($su[$uv]) * 100, 1);
		}
		#show3($o);
		
		$o = data::arrayfull($o);
		#$od = r_odds($fe, $av, $uv);
		#$k = array("p", "odds", "lower", "upper");
		#for ($j = 0; $j < count($k); ++$j) $o[$k[$j]][0] = $od[$k[$j]][0];
		#$o = comp3($o, "if (@p@ <= 0.05) @p@ = '<b>'.@p@.'</b>'; ");
		return $o;
	}
	
	function crossraw($fe, $av, $uv, $avschema = ""){
		$fe = data::get($fe, $av.",".$uv);
		
		$fe = data::comp($fe, "if (@$av@ == '') @$av@ = '999';");
		$fe = data::comp($fe, "if (@$uv@ == '') @$uv@ = '999';");
		
		if ($avschema == "") $st1 = data::uvstufen($fe, $av, 1); else $st1 = explode(",", kontrolliere($avschema));
		if ($uvschema == "") $st2 = data::uvstufen($fe, $uv, 1); else $st2 = explode(",", kontrolliere($uvschema));
		
		for ($i = 0; $i < count($st1); ++$i){
			for ($j = 0; $j < count($st2); ++$j){
				$u1 = $st1[$i]; $u2 = $st2[$j];
				
				$su = data::filter($fe, "@$uv@ == '$u2' and @$av@ !== ''");
				$fe2 = data::filter($fe, "@$av@ == '$u1' and @$uv@ == '$u2'");
				$nij = count2($fe2[$uv]); if ($nij == 0) $nij = "";
				
				$o["r_tmp_"][$i] = label::c($av, $u1);
				$o["c_tmp_".$u2][$i] = $nij;
			}
			for ($j = 0; $j < count($st2); ++$j) $o["tot"][$i] += $o["c_tmp_".$st2[$j]][$i];
		}
		$kf = array_keys($o);
		for ($j = 0; $j < count($kf); ++$j) {$v = $kf[$j]; $gr = count($o[$v]); if ($j == 0) $o[$v][$gr] = "total"; else $o[$v][$gr] = sum2($o[$v]);}
		
		$o = data::rename($o, "/^c_tmp_(.+)/", "\\1");
		$o = data::rename($o, "/^r_tmp_/", label::v($av));
		
		$kf = array_keys($o); $gr = count($kf); 
		for ($j = 1; $j < $gr; ++$j) $o = data::rename($o, "/^".$kf[$j]."$/", label::c($uv, $kf[$j]));
		$o = data::rename($o, "/^".$kf[$gr - 1]."/", label::v($kf[$gr - 1]));
		
		show3($o);
	}
	
	// $t->freq($fe, "gender");
	function freq($fe, $v){
		$a = data::vl($fe, $v);
		
		$s = new show();
		for ($j = 0; $j < count($a); ++$j) {
			$v = $a[$j];
			$ti = givelabel($v)." <font color = ".$this->color.">[".$v.", ".$v."_freq]</font>";
			
			if ($this->name == "") $this->name = $v."_freq";
			$ofi = ofi(currdb(), $this->name);
			
			if ($this->neu) {
				unset($o);
				for ($i = 0; $i < count($fe[$v]); ++$i) {$w = $fe[$v][$i]; if (trim($w) !== "") {$o[$v][$w] = $w; ++$o["n"][$w];}}
				if ($this->sort  == "") $o = data::sort($o, $v, "SORT_ASC"); else $o = data::sort($o, $this->sortcol, $this->sort); $o = self::array_values($o);
				$su = sum2($o["n"]);
				
				$o = data::comp($o, "@pr@ = @n@ / $su * 100;");
				$o = data::comp($o, "@pr@ = format2(@pr@, '0.0');");
				#$o = data::comp($o, "if (trim(@$v@) !== '') @$v@ = givelabel($v, @$v@);");
				$gr = count($o[$v]); $o[$v][$gr] = "total"; $o["n"][$gr] = $su; $o["pr"][$gr] = sum2($o["pr"]); $o = self::array_values($o);
				
				$o = data::comp($o, "@cum@ = '';");
				for ($i = 0; $i < count($o[$v]) - 1; ++$i){
					$o["cum"][$i] = $o["cum"][$i - 1] + $o["pr"][$i];
				}
				
				
				$fe = data::comp($fe, "if (trim(@$v@) == '') @mi_$v@ = 1;"); $mi = sum2($fe["mi_".$v]);
				if ($mi > 0){
					$o[$v][$gr + 1] = "."; $o["n"][$gr + 1] = $mi;
					$o[$v][$gr + 2] = "n"; $o["n"][$gr + 2] = count($fe[$v]);
				}
				write::fe($o, $ofi);
				
				$o2 = labelheaders3($o);
				show3($o2);
				#$m = new export; $m->type = "text"; $m->mysql($o2, $this->name);
				
				#echop($this->ti->nr($ti, $o));
				#$s->cols = $this->cols;
				#$s->save = $ofi;
				#$s->fe($o2);
				#writexls6($o2, $ofi.".xls", $cols = "30,10", $pos = "1,3", $fo = "@,0,0.0", "");
				
			} else {
				$o = flipn(read2d($ofi));
				echop($this->ti->nr($ti, $o));
				echop(read2($ofi.".html"));
			}
			
		}
	}
	
	function array_values($o){
		while (list($k, $col) = each($o)) $o[$k] = array_values($o[$k]); return $o;
	}
	
	function desc($fe, $v, $fu = "mean2,sd2,min2,median2,max2,count2"){
		$avf = data::vl($fe, $v);
		$ofi = ofi(currdb(), $this->name == "" ? fins($avf)."_desc" : $this->name);
		$ti .= label::set($avf[0])." <font color = ".$this->color.">[".fins($avf)."]</font>"; 
		if ($this->neu){
			$fuf = explode(",", $fu);
			for ($j = 0; $j < count($avf); ++$j){
				$v = $avf[$j];
				$g = $v.lz(5).label::v($v);
				$o["var"][$j] = $g; if ($varlb) $o[$a][$j] = $v." ".$g;
				for ($k = 0; $k < count($fuf); ++$k){
					$n = $fuf[$k];
					$o[$n][$j] = call_user_func_array($fuf[$k], array($fe[$v]));
				}
			}
			$o = data::fu($o, "mean|sd|med|min|max", "format2(@, '".$this->format."')");
			$o = data::fu($o, "^pm", "format2(@, '0.0')");
			$o2 = labelheaders3($o);
			
			$s = new show();
			$s->cols = $this->cols;
			$s->save = $ofi;
			echop($this->ti->nr($ti, $o)); write::fe($o, $ofi);
			$o = labelheaders3($o);
			if ($this->show) $s->fe($o);
			#writexls6($o2, $ofi.".xls", $cols = "20,10,10,10,10,12,10", $pos = "1,3", $fo = "@,0.0,0.0,0,0.0,0,0");
			return $o;
		} else {
			$o = read2d($ofi);
			$o = flipn($o);
			echop($this->ti->nr($ti, $o));
			echop(read2($ofi.".html"));
		}
	}
	
	function means($fe, $av, $uv, $fu = "mean2,sd2,min2,max2,count2"){
		$ofi = ofi(currdb(), $this->name == "" ? $uv."_means_".$av : $this->name);
		$uvf = data::vl($fe, $uv); $uv0 = $uvf[0];
		if (count($uvf) >= 1){
			$t = "uv";
			$fe = data::comp($fe, "@$t@ = ".concat3($fe, $uv).";");
			$uv = $t;
		}
		$st = data::uvstufen($fe, $uv);
		$avf = data::vl($fe, $av);
		$fuf = explode(",", $fu);
		for ($i = 0; $i < count($st); ++$i){
			$fe2 = data::filter($fe, "@$uv@ == '".$st[$i]."'");
			$o[$t][$i] = $st[$i]; #label::c($uvf[0], $st[$i]);
			for ($j = 0; $j < count($avf); ++$j){
				for ($k = 0; $k < count($fuf); ++$k){
					$v = $avf[$j]."_".$fuf[$k];
					$o[$v][$i] = call_user_func_array($fuf[$k], array($fe2[$avf[$j]]));
				}
			}
		}
		#if ($uv0 == $t) for ($j = 0; $j < count($uvf); ++$j) $o = data::comp($o, "@".$uvf[$j]."@ = explode('.', @$t@)[".$j."];");
		$o = data::fu($o, "mean|pm|sd|median|ci", "format2(@, '0.0')");
		$o = data::rename($o, "/^".$avf[0]."_/", "");
		$s = new show();
		$s->cols = $this->cols;
		$s->save = $ofi;
		echop($this->ti->nr("Deskriptive Werte <font color = ".$this->color.">[".fins($uvf).", ".fromto($ofi, "/out/", "")."]</font>", $o));
		write::fe($o, $ofi);
		$o = data::rename($o, "/^uv$/", givelabel($uvf[0]));
		$o = label::headers($o);
		if ($this->show) $s->fe($o);
		#writexls6($o, $ofi.".xls", $cols = "30,10", $pos = "1,3", $fo = $this->fo, $t);
		return $o;
	}
	
	// $t->cross3($fe, "c1", "c2", array(1,3,5), array(6,7,8));
	function alt_cross3($fe, $uv1, $uv2, $schema1, $schema2){
		$uv1 = vl3($fe, $uv1)[0]; $uv2 = vl3($fe, $uv2)[0];

		$le = 9999.9999;
		$fe = comp3($fe, "if (@$uv1@ == '') @$uv1@ = $le;");
		$fe = comp3($fe, "if (@$uv2@ == '') @$uv2@ = $le;");
		
		$st1 = uvstufen3($fe, $uv1); if (is_array($schema1)) $st1 = $schema1; $gr1 = count($st1);
		$st2 = uvstufen3($fe, $uv2); if (is_array($schema2)) $st2 = $schema2; $gr2 = count($st2);
		
		for ($i = 0; $i < count($st1); ++$i){
			for ($j = 0; $j < count($st2); ++$j){
				$fe2 = filter3($fe, "@$uv1@ == '$st1[$i]' and @$uv2@ == '$st2[$j]'");
				$nij = count2($fe2[$uv1]); if ($nij == 0) $nij = "";
				$o["r"][$i] = $st1[$i];
				$o["c".$st2[$j]][$i] = $nij;
			}
		}
		
		$gr = count($o["r"]);
		$o["r"][$gr] = "su";
		for ($j = 0; $j < count($st2); ++$j) {$n = "c".$st2[$j]; $o[$n][$gr] = sum2($o[$n]); }
		$o = comp3($o, "@su@ = sum2(array(".vl3s($o, "^c")."));");
		$o = comp3($o, "if (@r@ == $le) @r@ = '.';");
		$o = keyreplace3($o, "^c$le", "c.");
		show3($o);
	}

	// $t = new table(); $t->logistic(1, $fe, "gr", "^v[1-5]$");
	// Validierung zum SPSS
	// $fe = data::rnd(500, 5); $fe = comp3($fe, "is_even(@c01@) ? @gr@ = 1 : @gr@ = 0;");
	// for($j = 1; $j <= 5; ++$j) $fe = data::comp($fe, "if (preg_match('/[$j]/', @c0$j@)) @c0$j@ = '';");
	// $t = new table(); $t->logistic(1, $fe, "gr", "^c0"); $t->regression(1, $fe, "^c0", "gr");
	// $m = new export; $m->type = "real"; $m->mysql($fe, "test");
	function logistic($fe, $av, $uv, $fo = "0.00"){
		$l = chr(10);
		$db = currdb();
		$avf = data::vl($fe, $av); $av = implode(",", $avf);
		$uvf = data::vl($fe, $uv); $uv = implode(",", $uvf);
		$ofi = ofi(currdb(), "logistic_".$av."_by_".$uv."".$this->step); if ($this->name !== "") $ofi = "/eigenes/www/".$db."/out/".$this->name;
		$ti = givelabel($av).": Prädiktion mittels logistischer Regression <font color = ".$this->color.">[".fins($uvf)." by ".fins($avf)."]</font>";
		if ($this->neu) {
			if (file_exists($ofi)) unlink($ofi); if (file_exists($ofi.".html")) unlink($ofi.".html");
			$fe = data::get($fe, $uv.",".$av);
			$f = "/eigenes/downs/temp/tmp.dat"; export::asc($fe, $f);
			$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
			$r .= "ll <- glm($av ~ ".implode(" + ", $uvf).", data = x, family = binomial(link = 'logit'));".$l;
			if ($this->step == "backward") {$r .= "library(MASS); ll <- stepAIC(ll, direction = 'backward', trace = 0);".$l;}
			if ($this->step == "forward" ) {$r .= "library(MASS); min.ll = glm($av ~ 1, data = x); ll = step(min.ll, direction = 'forward', scope = formula(ll), trace = 0);".$l;}
			$r .= "sum.coef <- summary(ll)\$coef;
				odds <- exp(sum.coef[,1]);
				lower.ci <- exp(sum.coef[,1] - 1.96 * sum.coef[,2]);
				upper.ci <- exp(sum.coef[,1] + 1.96 * sum.coef[,2]);
				y <- data.frame(cbind(rownames(sum.coef)), sum.coef, odds, lower.ci, upper.ci, sum2(x\$$av), tot2(x\$$av));
				colnames(y) <- c('av', 'estim', 'se', 'z', 'p', 'or', 'lci', 'uci', 'nevents', 'n');
				write.table(y, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE);
			
				n = nrow(x); d = '%.3f';
				ll0 = glm($av ~ 1, data = x, family = 'binomial');
				McFadden = 1 - logLik(ll)/logLik(ll0);
				CoxSnell = 1 - exp((2 * (logLik(ll0) - logLik(ll)))/n);
				r2ML.max = 1 - exp(logLik(ll0) * 2/n);
				Nagelkerke = CoxSnell / r2ML.max;
				
				McFadden = sprintf(d, McFadden); #https://stats.stackexchange.com/questions/8511/how-to-calculate-pseudo-r2-from-rs-logistic-regressionl 
				CoxSnell = sprintf(d, CoxSnell);
				r2ML.max = sprintf(d, r2ML.max);
				Nagelkerke = sprintf(d, Nagelkerke);
				
				pred <- predict(ll, type = 'response');
				prob <- ifelse(pred < 0.5, 0, 1);
				x = cbind(x, data.frame(pred), prob);
				
				acc <- prop.ci2(ifelse(x\$prob == x\$$av, 1, 0));
				x2 = x[x\$$av  == 1, ]; tp  = prop.ci2(ifelse(x2\$prob == x2\$$av, 1, 0));
				x2 = x[x\$$av  == 0, ]; tn  = prop.ci2(ifelse(x2\$prob == x2\$$av, 1, 0));
				x2 = x[x\$prob == 1, ]; ppv = prop.ci2(ifelse(x2\$prob == x2\$$av, 1, 0));
				x2 = x[x\$prob == 0, ]; npv = prop.ci2(ifelse(x2\$prob == x2\$$av, 1, 0));
				
				library(ROCR); pred <- predict(ll); pr <- prediction(pred, x\$$av); prf <- performance(pr, measure = 'tpr', x.measure = 'fpr');
				auc <- performance(pr, measure = 'auc'); auc <- auc@y.values[[1]]; auc = sprintf(d, auc);
				
				z <- data.frame(append = c(paste('Pseudo-R²: McFadden = ', McFadden, ', Cox & Snell = ', CoxSnell, ', r²ML = ', r2ML.max,', Nagelkerke = ', Nagelkerke, 
					', Accuracy = ', acc, ', TP = ', tp, ', TN = ', tn, ', PPV = ', ppv, ', NPV = ', npv, ', AUC = ', auc, sep = '')));
				write.table(z, file = '$ofi.append', sep = '', quote = F, row.names = FALSE, col.names = FALSE);".$l;
				
			$r = leadingweg($r);
			$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
			#$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); exec("Rscript '$fi'");
			$fe = read3d($ofi);
			if ($this->sortcol !== "") $fe = data::sort($fe, $this->sortcol, SORT_DESC);
			$fe = data::filter($fe, "@av@ <> '' and @av@ <> '(Intercept)'");
			$fe = data::get($fe, "av,^p,or,lci,uci,nevents,n$,estim,se,z$");
			$fe = function_on_fe3($fe, "^or$|ci$", "format2(@, '0.0000')");
			$fe = function_on_fe3($fe, "^estim$|se$|z$", "format2(@, '0.0000')");
			$fe = function_on_fe3($fe, "^p$" , "format2(@, '0.0000')");
			$fe = data::comp($fe, "if (@p@ <= 0.05) @p@ = '<b>'.@p@.'</b>'; ");
			$fe = data::comp($fe, "@av@ = givelabel(@av@);");
			$fe = data::rename($fe, "/^av$/", "Logistic regression ".$this->step." (multivariat)");
			$fe = labelheaders3($fe);
			
			$s = new show();
			$s->cols = $this->cols;
			$s->borders = "^0$:.:s_o s_u,^last$:.:s_u s_o,2 to last:.:d_o";
			$s->save = $ofi;
			
			echop($this->ti->nr($ti, $fe));
			$s->fe($fe);
			
			$t = read2($ofi.".append"); echop($t);
			$fe["p-Wert"] = preg_replace("/\<\/?b\>/", "", $fe["p-Wert"]);
			writexls6($fe, $ofi.".xls", $cols = "30,10", $pos = "1,3", $fo = "@,0.0000,0.0000,0.0000,0.0000,0,0,0.000", $t);
		} else {
			$fe = flipn(read2d($ofi));
			echop($this->ti->nr($ti, $fe));
			echop(read2($ofi.".html"));
		}
	}
	
	// $t->logistic_single($fe, "tod", "age,sex");
	function logistic_single($fe, $av, $uv, $fo = "0.00"){ //jede uv geht einzeln in eine log. regression
		$l = chr(10);
		$uvf = data::vl($fe, $uv); $uv = implode(",", $uvf);
		$avf = data::vl($fe, $av); $av = implode(",", $avf);
		$ofi = ofi(currdb(), $uv."_logistic_single_".$av);
		if ($this->neu) {
			if (file_exists($ofi)) unlink($ofi); if (file_exists($ofi.".html")) unlink($ofi.".html");
			$fe = data::get($fe, $uv.",".$av);
			$f = "/tmp/tmp.dat"; export::asc($fe, $f);
			$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric);".$l;
			for($j = 0; $j < count($uvf); ++$j){
				$u = $uvf[$j];
				$r .= "x2 <- subset(x, complete.cases(x[c('$av','$u')]));".$l;
				$r .= "ll <- glm($av ~ $u, data = x2, family = binomial(link='logit'));".$l;
				$r .= "sum.coef<-summary(ll)\$coef;".$l;
				$r .= "odds<-exp(sum.coef[,1]);".$l;
				$r .= "lower.ci <- exp(sum.coef[,1] - 1.96*sum.coef[,2]);".$l;
				$r .= "upper.ci <- exp(sum.coef[,1] + 1.96*sum.coef[,2]);".$l;
				$r .= "y <- data.frame(cbind('$u', sum.coef, odds, lower.ci, upper.ci));".$l;
				$r .= "st <- length(unique(na.omit(x2\$$u)));".$l;
				$r .= "y <- cbind(y, row.names(y), st, sum2(x2\$$av), length(x2\$$u));".$l;
				$r .= "colnames(y) <- c('av', 'estim', 'se', 'z', 'p', 'or', 'lci', 'uci', 'name', 'st', 'nevents', 'n');".$l;
				if ($j == 0) $r .= "y2 <- y;".$l; else $r .= "y2 <- rbind(y2, y);".$l;
			}
			$r .= "write.table(y2, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); rm(list = ls());".$l;
			$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);

			$fe = read3d($ofi);
			$fe = data::filter($fe, "@av@ <> '' and @name@ <> '(Intercept)'");
			$fe = data::get($fe, "av,^p,or,lci,uci,nevents,n$");
			$fe = function_on_fe3($fe, "^or$|ci$", "format2(@, '".$this->format."')");
			$fe = function_on_fe3($fe, "^p$" , "format2(@, '0.0000')");
			$fe = data::comp($fe, "if (@p@ <= 0.05) @p@ = '<b>'.@p@.'</b>'; ");
			$fe = data::comp($fe, "@av@ = label::v(@av@);");
			$fe = data::rename($fe, "/^av$/", "Logistic regression (univariat)");
			write::fe($fe, $ofi);
			$fe = labelheaders3($fe);
			
			$s = new show();
			$s->cols = $this->cols;
			$s->borders = "^0$:.:s_o s_u,^last$:.:s_u s_o,2 to last:.:d_o";
			$s->save = $ofi;
			
			#echop($this->ti->nr($ti, $fe));
			echop($this->ti->nr("Logistic Regression <font color = ".$this->color.">[".$uv.", ".fromto($ofi, "/out/", "")."]</font>", $fe));
						
			$s->fe($fe);
			writexls4($fe, $ofi.".xls", $cols = "20,10,10,10,10,12,10", $pos = "1,2", $fo = "@,0.0000,0.0000,0.0000,0.0000,0,0"); #showxls($ofi.".xls");
		} else {
			$fe = flipn(read2d($ofi));
			echop($this->ti->nr("Logistic Regression <font color = ".$this->color.">[".$uv.", ".fromto($ofi, "/out/", "")."]</font>", $fe));
			echop(read2($ofi.".html"));
		}
	}
	
	function reptab_logrank($fe, $zeit, $event, $gruppe){ //Harrington, D. P. and Fleming, T. R. (1982). A class of rank test procedures for censored survival data. Biometrika 69, 553-566.
		$l = chr(10);
		$ofi = ofi(currdb(), $this->name == "" ? $gruppe."_reptab_logrank_".$zeit."_".$event : $this->name);
		if ($this->neu) {
			$zeit   = data::vl($fe, $zeit  ); $zeit   = implode(",", $zeit  );
			$event  = data::vl($fe, $event ); $event  = implode(",", $event );
			$gruppe = data::vl($fe, $gruppe); $gruppe = implode(",", $gruppe);
			$fe = data::get($fe, "^".$zeit."$,^".$event."$,^".$gruppe."$");
			$f = "/eigenes/downs/temp/tmp.dat"; export::asc($fe, $f);
			$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
			$r .= "library(splines); library(survival);".$l;
			
			$stf = data::uvstufen($fe, $gruppe); $stc = count($stf); $z = 0;
			for ($j = 0; $j < $stc; ++$j){
				for ($i = 0; $i < $stc; ++$i){
					if ($i > $j) {
						++$z;
						$c1 = $stf[$j]; $c2 = $stf[$i];

						$r .= "x2 = subset(x, subset = $gruppe %in% c($c1, $c2));".$l;
						$r .= "l <- survdiff(Surv($zeit, $event) ~ $gruppe, data = x2, rho = 0);".$l;  //rho 0 =  log-rank (Mantel-Haenzel), 1 = Peto & Peto modification of the Gehan-Wilcoxon test
						$r .= "p <- round(1 - pchisq(l\$chisq, length(l\$n) - 1), 4);".$l;
						$r .= "n <- l\$n;".$l;
						$r .= "hr <- (l\$obs[2] / l\$exp[2]) / (l\$obs[1] / l\$exp[1]);".$l;
						$r .= "hrl <- exp(log(hr) - qnorm(0.975) * sqrt(1 / l\$exp[2] + 1 / l\$exp[1]) );".$l;
						$r .= "hru <- exp(log(hr) + qnorm(0.975) * sqrt(1 / l\$exp[2] + 1 / l\$exp[1]) );".$l;
						$r .= "n1 <- count2(subset(x2, $gruppe %in% $c1));".$l;
						$r .= "n2 <- count2(subset(x2, $gruppe %in% $c2));".$l;

						$r .= "t <- cbind('$c1', 'vs.', '$c2', n1, n2, p, hr, hrl, hru); colnames(t) <- c('Logrank-Tests', 'vs', 'group', 'n1', 'n2', 'p', 'or', 'lower', 'upper');  ".$l;
						if ($z == 1) $r .= "t2 <- t;".$l; else $r.= "t2 <- rbind(t2, t);".$l;
					}
				}
			}
			$r .= "write.table(t2, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); rm(list = ls());".$l;
			$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); exec("sudo Rscript '$fi'"); #$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
			$fe = read3d($ofi);
			
			$fe = data::comp($fe, "if (is_numeric(@p@)) @p@ = format2(@p@, '0.0000');");
			$fe = data::comp($fe, "if (@p@ < 0.0001) @p@ = '< 0.0001';");
			$fe = data::comp($fe, "if (@p@ <= 0.05) @p@ = '<b>'.@p@.'</b>';");
			$fe = data::comp($fe, "if (@or@ > 50) @or@ = '<center> - </center>';");
			$fe = data::fu($fe, "^or|lower|upper", "format2(@, '0.0000')");
			$fe = label::col($fe, "Logrank-Tests", $gruppe);
			$fe = label::col($fe, "group", $gruppe);
			write::fe($fe, $ofi);
			$s = new show();
			$s->cols = $this->cols;
			$s->borders = "^0$:.:s_o s_u,^last$:.:s_u s_o,2 to last:.:d_o";
			$s->save = $ofi;
			echop($this->ti->nr("Explorativer Logrank-Test <font color = ".$this->color.">[".$gruppe.", ".fromto($ofi, "/out/", "")."]</font>", $fe));
			$fe = label::headers($fe);
			$s->fe($fe);
			$fe = data::fu($fe, "^p", "preg_replace('/\<b\>|\<\/b\>/', '', @)");
			writexls6($fe, $ofi.".xls", $cols = "20,10", $pos = "1,3", $fo = "@,@,@,0,0,0.0000,0.0000");
			
		} else {
			$fe = read3d($ofi);
			echop($this->ti->nr("Explorativer Logrank-Test <font color = ".$this->color.">[".$gruppe.", ".fromto($ofi, "/out/", "")."]</font>", $fe));
			echop(read2($ofi.".html"));
		}
	}	

	// $t = new table(); $t->regression($fe, "krit", "^praed1,praed2");
	function regression($fe, $av, $uv, $pred = ""){
		$l = chr(10);
		$avf = data::vl($fe, $av); $av = implode(",", $avf);
		$uvf = data::vl($fe, $uv); $uv = implode(",", $uvf);
		$ofi = ofi(currdb(), "regression_on_".fins($avf));
		exec("rm /eigenes/www/".currdb()."/out/regression_on_".$av."*");
		
		$ti = label::set($avf[0]).": multiple lineare Regression zur Prädiktion. <font color = ".$this->color.">[".fins($uvf)." auf ".fins($avf)."]</font>";
		
		if ($this->neu){
			$fe = data::get($fe, $uv.",".$av);
		
			$f = "/eigenes/downs/temp/regr.dat"; export::asc($fe, $f);
			$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
			
			//coeffs
			$u = preg_replace("/,/", " + ", $uv);
			$r .= "rg <- lm($av ~ $u, data = x);".$l;
			if ($this->step !== "") {$r .= "library(MASS); rg <- stepAIC(rg, direction = '".$this->step."', trace = 0);".$l;}
			$r .= "su <- summary(rg);".$l;
			$r .= "c1 <- su[4];".$l;
			$r .= "c2 <- confint(rg, level = 0.95);".$l;
			$r .= "c12 <- cbind(data.frame(c1),data.frame(c2));".$l;
			$r .= "colnames(c12) <- c('av".chr(9)."coeffs', 'se', 'tvalue', 'p', 'lo_c', 'up_c');".$l;
			$r .= "write.table(c12, file = '$ofi', sep = '\\t', quote = F); ".$l;
			
			//model
			$r .= "r2 <- su\$r.squared; f <- su\$fstatistic[1]; df1 <- su\$fstatistic[2]; df2 <- su\$fstatistic[3];".$l;
			$r .= "p <- pf(f, df1, df2, lower.tail = FALSE);".$l; //pf berechnet p-Wert aus F, df1, df2
			$r .= "p2 <- sprintf('%.4f', p);  if (p <= 0.05) p2 <- paste('<b>p =', p2, '</b>'); if (p < 0.0001) p2 <- '<b>p < 0.0001</b>';  ".$l;
			$r .= "RSME = sqrt(mean(rg\$residuals^2));".$l;
			$r .= "stats <- data.frame(paste('model ', p2, ', R² =', round(r2, 4), ', RSME = ', round(RSME, 4), ', df1 = ', df1, ', df2 = ', df2, sep = ''));".$l;
			$r .= "colnames(stats) <- c('var');".$l;
			$r .= "write.table(stats, file = '".$ofi."_model', sep = ' ', quote = F); ".$l;
			
			//regression diagnostics  www.statmethods.net/stats/rdiagnostics.html, test for normality residuals
			$r .= "sh1 <- shapiro.test(rg\$resid[0:4999]);".$l;
			$r .= "if (sh1\$p.value <  0.0001) shp <- ', p < 0.0001';".$l;
			$r .= "if (sh1\$p.value >= 0.0001) shp <- paste(', p = ', round(sh1\$p.value, 4));".$l; 
			$r .= "sh2 <- cbind('w = ', round(sh1\$statistic, 3), shp)".$l;
			$r .= "write.table(sh2, file = '".$ofi."_normality', sep = ' ', quote = F, row.names = FALSE, col.names = FALSE); ".$l;
			
			//nonconstant variances
			$r .= "library(car); nc <- ncvTest(rg); ".$l;
			$r .= "chi <- sprintf('%.4f', nc[3]);".$l;
			$r .= "p   <- sprintf('%.4f', nc[5]);".$l;
			$r .= "nc <- data.frame(paste('Chi = ', chi, ', p =', p));".$l;
			$r .= "colnames(nc) <- c('nconstvar');".$l;
			$r .= "write.table(nc, file = '".$ofi."_varianceheterogeneity', sep = ' ', quote = F, row.names = FALSE, col.names = FALSE); ".$l;
			
			//multicollinearity, wenn > 2
			$r .= "vifl <- round(sqrt(vif(rg)),3);".$l;
			$r .= "write.table(vifl, file = '".$ofi."_multicollinearity', sep = ' ', quote = F, row.names = TRUE, col.names = FALSE); ".$l;

			//save predicted
			if ($pred <> "") {
				$r .= "pred <- data.frame(x, preds = round(rg\$fitted.values,2), resids = round(rg\$residuals,2))".$l;
				$r .= "write.table(pred, file = '$pred', sep = ' ', quote = F, row.names = FALSE, col.names = TRUE); ".$l;
			}
			
			$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); exec("sudo Rscript '$fi'"); // $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
			$fe = read3d($ofi);
			
			$fe = data::filter($fe, "@av@ <>''");
			$fe = function_on_fe3($fe, "^p$", "format2(@, '0.0000')");
			$fe = function_on_fe3($fe, "^coeffs|^se|^tvalue|_c", "format2(@, '0.000')");
			$fe = data::get($fe, "av,^p,coeffs,lo_c,up_c,se,tvalue");
			for ($i = 0; $i < count($fe["av"]); ++$i) $fe["av"][$i] = label::v($fe["av"][$i]);
			
			$fe = data::comp($fe, "if (@p@ <= 0.05) @p@ = '<b>'.@p@.'</b>';");
			$fe = label::headers($fe); $fe0 = $fe;
						
			$cs = "colspan"; $cl = "class = sr"; $al = "align = center";
			$t = "<tr class = s_o ><td></td><td $cl $cs = 6 $al>Multiple Regression: Prädiktion der <b>".label::v($av)."</b></td></tr>";
			
			$s = new show();
			$s->cols = $this->cols;
			$s->borders = "^0$:.:s_u,.:.:d_o,last:.:s_u";
			$s->save = $ofi;
			$s->firstrow = $t;
			$s->fe($fe);
			
			//assumption tests
			$t  = "Signifikanz des Gesamtmodells: ".fromto(read2($ofi."_model"), "model", "");
			$t .= "<br><b>Prüfung der Annahmen der multiplen Regression (bei Verletzungen wird das Modell nur deskriptiv verwendet)</b>";
			
			$t2 = read2($ofi."_normality"); $t2 = preg_replace("/\\t/", "", $t2);
			$t .= "<br>1. Shapiro-Wilks-Test auf Abweichung von der Normalverteilungsannahme: $t2 (der Residuen, verletzt wenn p <u><</u> 0.05).";
			
			$t2 = read2($ofi."_varianceheterogeneity"); $t2 = preg_replace("/\\s, /", ", ", $t2);
			$t .= "<br>2. Abweichung im Sinne nicht-konstanter, d.h. heterogener, Varianzen $t2 (Annahme verletzt, wenn p <u><</u> 0.05).";

			$t2 = read2($ofi."_multicollinearity");
			$t2 = preg_replace("/\\n/", ", ", $t2);
			$t2 = kommaweg(trim($t2));
			$t2 = givelabel_list3($t2);
			$t .= "<br>3. Multicollinearitätswert: $t2 (Annahme verletzt, wenn > 2): ";
			
			//regression equation
			$fe = read3d($ofi);

			$gr1 = count($fe["av"]);
			for ($i = 0; $i < $gr1 - 1; ++$i) $e[] = round($fe["coeffs"][$i],3)." * ".label::v($fe["av"][$i]);
			$e = preg_replace("/\* \(Intercept\)/", "", $e);
			$e = preg_replace("/\+ \-/", " - ", $e);
			$t .= "<br><b>Resultierende Regressionsgleichung: ".label::v($avf[0])." = ".implode(" + ", $e)."</b>";
			$t = preg_replace("/\+ \-/", " - ", $t);
			echop($t); write2($t, $ofi."_nachtab");
			
			$t = preg_replace("/\\n/", "", $t);
			$t = preg_replace("/\<br\>/", chr(10), $t);
			$t = preg_replace("/\<[bu]r?\>|\<\/[bu]\>/", "", $t);
			myq("insert into ".$this->name." (av) values ('$t')");
			
			#show3($fe0);
			writexls6($fe0, $ofi.".xlsx", $cols = "20,10", $pos = "1,2", $fo = $this->format);
			
			
		} else {
			$o = read3d($ofi);
			echop($this->ti->nr($ti, $o));
			echop(read2($ofi.".html"));
			echop(read2($ofi."_nachtab"));
		}
	}
	
	function coxregression_single($fe, $zeit, $event, $covs){
		$l = chr(10);
		$covsf = data::vl($fe, $covs ); $covs  = implode(",", $covsf);
		$ofi = ofi(currdb(), $this->name == "" ? $covs."_cox_single_".$zeit."_".$event : $this->name);
		if ($this->neu){
			$zeit  = data::vl($fe, $zeit ); $zeit  = implode(",", $zeit);
			$event = data::vl($fe, $event); $event = implode(",", $event);
			$fe = data::get($fe, $zeit.",".$event.",".$covs);
			
			$f = "/eigenes/downs/temp/coxregr.dat"; export::asc($fe, $f);
			$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric);".$l;
			$r .= "library(splines); library(survival);".$l;
			
			for($j = 0; $j < count($covsf); ++$j){
				$c = $covsf[$j];
				$r .= "c1 <- coxph(formula = Surv($zeit, $event) ~ $c, data = x);".$l;
				$r .= "c2 <- summary(c1);".$l;
				$r .= "c3 <- data.frame(cbind(c2\$coefficients, c2\$conf.int))[, c(5,6,8,9,1,3,4)];".$l;
				$r .= "n <- nrow( data.frame(c1[7]));".$l;
				$r .= "c4 <- data.frame(cbind(rownames(c3), c3, c1\$nevent, n));".$l;
				$r .= "colnames(c4) <- c('av', 'p', 'or', 'lci', 'uci', 'estim', 'se', 'z', 'nevents', 'n');".$l;
				
				if ($j == 0) $r .= "cc <- c4;".$l; else $r .= "cc <- rbind(cc, c4);".$l;
			}
			$r .= "write.table(cc, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); ".$l;
			$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
			$fe = read3d($ofi);
			if ($this->sortcol !== "") $fe = data::sort($fe, "or", SORT_DESC);
			$fe0 = $fe;
			$fe = data::fu($fe, "^or|ci|^estim|^se|^z", "format2(@, '0.0000')");
			$fe = data::fu($fe, "^p" , "format2(@, '0.0000')");
			$fe = data::comp($fe, "if (@p@ <= 0.05) @p@ = '<b>'.@p@.'</b>';");
			$fe = data::comp($fe, "@av@ = givelabel(@av@);");
			$fe = data::rename($fe, "/^av$/", "Cox regression (univariat)");
			write::fe($fe, $ofi);
			
			$s = new show();
			$s->cols = $this->cols;
			$s->borders = "^0$:.:s_o s_u,^last$:.:s_u s_o,2 to last:.:d_o";
			$s->save = $ofi;
			echop($this->ti->nr("Cox-Regression <font color = ".$this->color.">[".$covs.", ".fromto($ofi, "/out/", "")."]</font>", $fe));
			$fe = labelheaders3($fe);
			$s->fe($fe);
			
			$fe["p-Wert"] = preg_replace("/\<\/?b\>/", "", $fe["p-Wert"]);
			writexls6($fe, $ofi.".xls", $cols = "20,10,10,10,10,12,10", $pos = "1,2", $fo = "@,0.0000,0.0000,0.0000,0.0000,0,0");
			return $fe0;
		} else {
			$fe = flipn(read2d($ofi));
			echop($this->ti->nr("Cox-Regression <font color = ".$this->color.">[".$covs.", ".fromto($ofi, "/out/", "")."]</font>", $fe));
			echop(read2($ofi.".html"));
		}
	}
	
	// Vergleich zweier Modelle mit anova
	//   fit1 <- coxph(Surv(futime, fustat) ~ resid.ds * rx + ecog.ps, data = ovarian); fit2 <- coxph(Surv(futime, fustat) ~ resid.ds + rx + ecog.ps, data = ovarian); anova(fit2, fit1);
	// coxregression(1, $fe, "zeit", "tod", "age,sex,bmi");
	// assumptions https://rpubs.com/kaz_yos/coxph-residuals
	// Literatur
	// Fleming, T. H. and Harrington, D. P. (1984). Nonparametric estimation of the survival distribution in censored data. Comm. in Statistics 13, 2469-86.
	// Kablfleisch, J. D. and Prentice, R. L. (1980). The Statistical Analysis of Failure Time Data. New York:Wiley.
	// Link, C. L. (1984). Confidence intervals for the survival function using Cox's proportional hazards model with covariates. Biometrics 40, 601-610.
	// Therneau T and Grambsch P (2000), Modeling Survival Data: Extending the Cox Model, Springer-Verlag.
	// Tsiatis, A. (1981). A large sample study of the estimate for the integrated hazard function in Cox's regression model for survival data. Annals of Statistics 9, 93-108. 
	
	function coxregression($fe, $zeit, $event, $covs){
		$l = chr(10);
		$db = currdb();
		
		$ofi = ofi($db, "coxregression_".$covs); 
		if ($this->name !== "") $ofi = "/eigenes/www/".$db."/out/".$this->name;
		#$ofi = "/eigenes/downs/temp/cox_iii.tb";
		
		$zeit  = data::vl($fe, $zeit ); $zeit  = implode(",", $zeit);
		$event = data::vl($fe, $event); $event = implode(",", $event);
		$covsf = data::vl($fe, $covs ); $covs  = implode(",", $covsf);
		$fe = data::get($fe, $zeit.",".$event.",".$covs);
		if ($this->neu == 1) {
			$f = "/eigenes/downs/temp/coxregr.dat"; export::asc($fe, $f);
			$r .= "x <- read.table('$f', header = TRUE, stringsAsFactors = FALSE); x[] <- lapply(x, as.numeric);  x <- subset(x, complete.cases(x));".$l;
 
			$r .= "require(survival);".$l;
			$r .= "c0 <- coxph(formula = Surv($zeit, $event) ~ ".implode(" + ", $covsf).", data = x, model = TRUE);".$l;
			if ($this->step !== "") $r .= "library(MASS); c1 <- stepAIC(c0, direction = '".$this->step."');".$l; else $r .= "c1 <- c0;".$l;
			$r .= "c2 <- summary(c1);".$l;
			$r .= "c3 <- data.frame(cbind(c2\$coefficients, c2\$conf.int))[, c(5,6,8,9,1,3,4)];".$l;
			$r .= "n <- nrow( data.frame(c1[7]));".$l;
			$r .= "c4 <- data.frame(cbind(rownames(c3), c3, c1\$nevent, n), stringsAsFactors = FALSE);".$l;
			$r .= "colnames(c4) <- c('av', 'p', 'or', 'lci', 'uci', 'estim', 'se', 'z', 'nevents', 'n');".$l;
			
			$r .= "write.table(c4, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE);".$l;
			
			$r .= "pred <- 1 - exp(-predict(c1, type = 'expected')); prob <- ifelse(pred < 0.5, 0, 1); x = cbind(x, data.frame(pred), data.frame(prob));".$l;
			$r .= " rsq = round(c2\$rsq[1], 4);
				acc <- prop.ci2(ifelse(x\$prob == x\$$event, 1, 0));
				x2 = x[x\$$event == 1, ]; tp  = prop.ci2(ifelse(x2\$prob == x2\$$event, 1, 0));
				x2 = x[x\$$event == 0, ]; tn  = prop.ci2(ifelse(x2\$prob == x2\$$event, 1, 0));
				x2 = x[x\$prob   == 1, ]; ppv = prop.ci2(ifelse(x2\$prob == x2\$$event, 1, 0));
				x2 = x[x\$prob   == 0, ]; npv = prop.ci2(ifelse(x2\$prob == x2\$$event, 1, 0));".$l;
			$r .= "c5 <- data.frame(append = c(paste('R² = ', rsq, ', Accuracy = ', acc, ', TP = ', tp, ', TN = ', tn, ', PPV = ', ppv, ', NPV = ', npv, sep = '')));".$l;
			$r .= "write.table(c5, file = '$ofi.append', sep = ', ', quote = F, row.names = FALSE, col.names=FALSE);".$l;
			$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); exec("sudo Rscript '$f'"); //$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
			$r = leadingweg($r);
			$fe = read3d($ofi);
			
			if ($this->sortcol !== "") $fe = data::sort($fe, "or", SORT_DESC);
			$fe0 = $fe;
			
			$fe = data::fu($fe, "^or|ci|estim|se|z", "format2(@, '0.0000')");
			$fe = data::fu($fe, "^p" , "format2(@, '0.0000')");
			$fe = data::comp($fe, "if (@p@ <= 0.05) @p@ = '<b>'.@p@.'</b>';");
			$fe = data::comp($fe, "@av@ .= ' '.label::v(@av@);");
			$fe = data::rename($fe, "/^av$/", "Cox regression ".$this->step." (multivariat)");
			#$fe = labelheaders3($fe);
			write::fe($fe, $ofi);
			
			$s = new show();
			$s->cols = $this->cols;
			$s->borders = "^0$:.:s_o s_u,^last$:.:s_u s_o,2 to last:.:d_o";
			$s->save = $ofi;
			echop($this->ti->nr("Cox-Regression <font color = ".$this->color.">[".$covs.", ".fromto($ofi, "/out/", "")."]</font>", $fe));
			$fe = label::headers($fe);
			$s->fe($fe);
			
			$t = read2($ofi.".append"); echop($t);
			
			$fe = data::fu($fe, "^p", "preg_replace('/\<b\>|\<\/b\>/', '', @)");
			$t = str_replace(chr(34), "", $t);
			#writexls6($fe, $ofi.".xls", $cols = "30,8", $pos = "1,2", $fo = "@,0.0000,0.0000,0.0000,0.0000,0.0000,0.0000,0.0000,0,0", $t);
			return $fe0;
		} else {
			$fe = flipn(read2d($ofi));
			echop($this->ti->nr("Cox-Regression <font color = ".$this->color.">[".$covs.", ".fromto($ofi, "/out/", "")."]</font>", $fe));
			echop(read2($ofi.".html"));
			$t = read2($ofi.".append"); $t = preg_replace("/\\n/", "<br>", $t); echop($t);
		}
	}
	
	function coxregression2($fe, $zeit, $event, $covs){
		$l = chr(10);
		$db = currdb();
		
		if ($this->name == "") $this->name = "cox";
		
		$zeit  = data::vl($fe, $zeit ); $zeit  = implode(",", $zeit);
		$event = data::vl($fe, $event); $event = implode(",", $event);
		$covsf = data::vl($fe, $covs ); $covs  = implode(",", $covsf);
		$fe = data::get($fe, $zeit.",".$event.",".$covs);

		$n = "n".str::zufall(5); myq("drop table if exists $n");
		
		$f = "/eigenes/downs/temp/coxregr.dat"; export::asc($fe, $f);
		$r .= "x <- read.table('$f', header = TRUE, stringsAsFactors = FALSE); x[] <- lapply(x, as.numeric);  x <- subset(x, complete.cases(x));".$l;
		$r .= "require(survival); require(DBI); require(binom);".$l;
 
		$r .= "c0 <- coxph(formula = Surv($zeit, $event) ~ ".implode(" + ", $covsf).", data = x, model = TRUE);".$l;
		if ($this->step !== "") $r .= "library(MASS); c1 <- stepAIC(c0, direction = '".$this->step."');".$l; else $r .= "c1 <- c0;".$l;
		$r .= "c2 <- summary(c1);".$l;
		$r .= "c3 <- data.frame(cbind(c2\$coefficients, c2\$conf.int))[, c(5,6,8,9,1,3,4)];".$l;
		$r .= "n <- nrow( data.frame(c1[7]));".$l;
		$r .= "c4 <- data.frame(cbind(rownames(c3), c3, c1\$nevent, n), stringsAsFactors = FALSE);".$l;
		$r .= "colnames(c4) <- c('av', 'p', 'or', 'lci', 'uci', 'estim', 'se', 'z', 'nevents', 'n');".$l;
 
		$r .= "con = dbConnect(MySQL(), user = 'backuser', password = 'backuser99', dbname = '$db', host = '192.168.178.42');".$l;
 		$r .= "w = dbWriteTable(con, name = '$n', value = c4, overwrite = 1, row.names = FALSE);".$l;
			
		$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); exec("sudo Rscript '$fi'");
		
		show3(import::mysql("select * from $n"));
	}
		
	
	function coxregressionbackward($fe, $zeit, $event, $cov){
		$l = chr(10);
		$db = currdb();
		$ofi = ofi($db, "coxback_".$cov); if ($this->name !== "") $ofi = "/eigenes/www/".$db."/out/".$this->name;
		$zeit  = data::vl($fe, $zeit ); $zeit  = implode(",", $zeit);
		$event = data::vl($fe, $event); $event = implode(",", $event);
		$covsf = data::vl($fe, $cov ); $cov  = implode(",", $covsf);
		$fe = data::get($fe, $zeit.",".$event.",".$cov);
		if ($this->neu == 1) {
			$f = "/eigenes/downs/tmp/coxback.dat"; 
			$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric);".$l;
			$r .= "require(survival);".$l;
			$fe = data::get($fe, $zeit.",".$event.",".$cov);
			$cov = preg_replace("/,/", " + ", $cov);
			export::asc($fe, $f);
			$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
			$r .= "c0 <- coxph(formula = Surv($zeit, $event) ~ $cov, data = x);".$l;
			$r .= "library(MASS); c1 <- stepAIC(c0, direction = 'backward'); c2 <- summary(c1);".$l;
			$r .= "c3 <- data.frame(cbind(rownames(c2\$coefficients), c2\$coefficients, c2\$conf.int))[, c(1,6,7,9,10,2,4)];".$l;
			$r .= "colnames(c3) <- c('av', 'p', 'or', 'lci', 'uci', 'coeff', 'se');".$l;
			$r .= "write.table(c3, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); ".$l;

			$r .= "pred <- 1 - exp(-predict(c1, type = 'expected')); prob <- ifelse(pred < 0.5, 0, 1); x = cbind(x, data.frame(pred), data.frame(prob));".$l;
			$r .= " rsq = round(c2\$rsq[1], 4);
				acc <- prop.ci2(ifelse(x\$prob == x\$$event, 1, 0));
				x2 = x[x\$$event == 1, ]; tp  = prop.ci2(ifelse(x2\$prob == x2\$$event, 1, 0));
				x2 = x[x\$$event == 0, ]; tn  = prop.ci2(ifelse(x2\$prob == x2\$$event, 1, 0));
				x2 = x[x\$prob   == 1, ]; ppv = prop.ci2(ifelse(x2\$prob == x2\$$event, 1, 0));
				x2 = x[x\$prob   == 0, ]; npv = prop.ci2(ifelse(x2\$prob == x2\$$event, 1, 0));".$l;
			$r .= "c5 <- data.frame(append = c(paste('RSquare = ', rsq, ', Accuracy = ', acc, ', TP = ', tp, ', TN = ', tn, ', PPV = ', ppv, ', NPV = ', npv, sep = '')));".$l;
			$r .= "write.table(c5, file = '$ofi.append', sep = '\\t', quote = F, row.names = FALSE, col.names=FALSE);".$l;
			$r .= "rm(x); rm(x2); rm(c0); rm(c1); rm(c2); rm(c3); rm(c5); rm(pred); rm(acc);".$l;
			$fi = "/eigenes/downs/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
		
			$fe = read3d($ofi);
			$fe = data::fu($fe, "^or|ci|coeff|se|z", "format2(@, '0.00')");
			$fe = data::fu($fe, "^p" , "format2(@, '0.0000')");
			$fe = data::comp($fe, "if (@p@ <= 0.05) @p@ = '<b>'.@p@.'</b>';");
			$fe = data::comp($fe, "@av@ = givelabel(@av@);");
			$fe = data::rename($fe, "/^av$/", "Cox regression (multivariat & backward)");
			if ($this->sortcol !== "") $fe = data::sort($fe, "or", SORT_DESC);
			$fe0 = $fe; write::fe($fe, $ofi);
			$s = new show();
			$s->cols = $this->cols;
			$s->borders = "^0$:.:s_o s_u,^last$:.:s_u s_o,2 to last:.:d_o";
			$s->save = $ofi;
			echop($this->ti->nr("Cox-Regression (backward) <font color = ".$this->color.">[".$covs.", ".fromto($ofi, "/out/", "")."]</font>", $fe));
			$fe = label::headers($fe);
			$s->fe($fe);
			$t = read2($ofi.".append"); $t = preg_replace("/\\n/", "<br>", $t); echop($t);
			#writexls4($fe, $ofi.".xls", $cols = "20,10,10,10,10,12,10", $pos = "1,2", $fo = "@,0.0000,0.0000,0.0000,0.0000,0,0"); #showxls($ofi.".xls");
			return $fe0;
		} else {
			$fe = flipn(read2d($ofi));
			if ($this->sortcol !== "") $fe = data::sort($fe, "or", SORT_DESC);
			echop($this->ti->nr("Cox-Regression (backward) <font color = ".$this->color.">[".$covs.", ".fromto($ofi, "/out/", "")."]</font>", $fe));
			show3($fe);
			$t = read2($ofi.".append"); $t = preg_replace("/\\n/", "<br>", $t); echop($t);
		}
	}
	
	function km_cox_log_tab($fe, $zeit, $tod_cox, $tod_log, $v){
		$vf = data::vl($fe, $v);
		$el = implode(",", array_slice($vf, 0, 5));
		$db = currdb();
		$ofi = ofi($db, "km_cox_log_tab_".$el);
		$ofi = ofi(currdb(), "km_cox_log_tab_".$v); if ($this->name !== "") $ofi = "/eigenes/www/".$db."/out/".$this->name;
		
		if ($this->neu) {
			for ($j = 0; $j < count($vf); ++$j) {
				$a = $vf[$j];
				
				$m = data::agg($fe, $tod_log, $a, "pm2,sum2,count2"); $m = data::comp($m, "@alle@ = 1;"); #show3($m);
				$m = data::casestovars($m, "alle", $a, "pm2,sum2,count2"); #show3($m);
				
				$k = kmvalue($fe, $zeit, $tod_cox, $a);  #show3($k);
				$c = coxvalue($fe, $zeit, $tod_cox, $a); #show3($c);
				$l = logisticvalue($fe, $tod_log, $a);   #show3($l);
				
				$tb = data::merge(array($k, $m, $c, $l), "alle");
				
				if ($j == 0) $tb2 = $tb; else $tb2 = data::add(array($tb2, $tb));
				
			}
			if ($this->sortcol !== "") $tb2 = data::sort($tb2, $this->sortcol, SORT_DESC);
			if ($this->filter !== "") {
				$tb2 = data::lfn($tb2);
				$tb2 = data::filter($tb2, $this->filter); 
				unset($tb2["lfn"]);
			}
			
			$tb2 = data::fu($tb2, "^p_|^or_|ci_", "format2(@, '0.00')");
			$tb2 = data::fu($tb2, "^su", "format2(@, '0')");
			$tb2 = data::get($tb2, "var_cox,su_1,su_0,pm2_1,sum2_1,count2_1,pm2_0,sum2_0,count2_0,p_cox,or_cox,lci_cox,uci_cox,p_log,or_log,lci_log,uci_log");
			
			$tb2 = data::comp($tb2, "if (@p_cox@ <= 0.05) @p_cox@ = '<b>'.@p_cox@.'</b>';");
			$tb2 = data::comp($tb2, "if (@p_log@ <= 0.05) @p_log@ = '<b>'.@p_log@.'</b>';");
			$tb2 = data::comp($tb2, "@var_cox@ = givelabel(@var_cox@);");
			
			$tb3 = flip3($tb2);
			$tb3[0] = preg_replace("/su_[01]_(.+)/", "\\1.", $tb3[0]);
			$tb3[0] = preg_replace("/_cox|_log/", "", $tb3[0]);
			$tb3[0] = preg_replace("/".$tod_log."_/", "", $tb3[0]);
			$tb3[0] = preg_replace(array("/pm2_(.+)/", "/sum2_(.+)/", "/count2_(.+)/"), array("%", "Fälle", "n"), $tb3[0]);
			$tb3[0][0] = "";
		}
		$s = new show();
		$s->cols = $this->cols;
		$s->borders = "^0$:.:s_o s_u,^last$:.:s_u s_o,2 to lastbut1:.:d_o,.:^[48]$|^1[148]$:s_r";
		$o = " class = s_o ";
		$s->firstrow = "<tr><td class = s_o></td>
					<td ".$o.cs(4).">kumulative<br>Sterbe-Inzidenz</td>
					<td ".$o.cs(4).">Inzidenz der<br>übrigen Patienten</td>
					<td ".$o.cs(3).">30-Tages-Sterberate</td>
					<td ".$o.cs(3).">Rate der<br>übrigen Patienten</td>
					<td ".$o.cs(4).">Cox Regression<br>(Überleben insgesamt)</td>
					<td ".$o.cs(4).">Logistische Regression<br>(30-Tages-Überleben)</td>
				</tr>"; 
		$s->fe0($tb3);

		$tb3 = rowinsert($tb3,0);
		$tb3[0] = seq("vc1-vc9,vc10-vc23");
		$tb4 = flipn($tb3);
		
		writexls6($tb4, $ofi.".xls", $cols = "20,5", $pos = "1,2", $fo = "@,0,0,0,0,0,0,0,0,0.0,0,0,0.0,0,0,0.00");
		return $tb3;
	}
	
	function cox_log_tab($fe, $zeit, $tod_cox, $tod_log, $v){
		$vf = data::vl($fe, $v);
		$el = implode(",", array_slice($vf, 0, 5));
		$db = currdb();
		$ofi = ofi(currdb(), "cox_log_tab_".$v); if ($this->name !== "") $ofi = "/eigenes/www/".$db."/out/".$this->name;
		
		if ($this->neu) {
			for ($j = 0; $j < count($vf); ++$j) {
				$a = $vf[$j];
				
				$m = data::agg($fe, $tod_log, $a, "pm2,sum2,count2"); $m = data::comp($m, "@alle@ = 1;"); #show3($m);
				$m = data::casestovars($m, "alle", $a, "pm2,sum2,count2"); #show3($m);
				
				$c = coxvalue($fe, $zeit, $tod_cox, $a); #show3($c);
				$l = logisticvalue($fe, $tod_log, $a);   #show3($l);
				
				$tb = array_merge($c, $l); #$tb = data::merge(array($c, $l), "alle");
				
				if ($j == 0) $tb2 = $tb; else $tb2 = data::add(array($tb2, $tb));
				
			}
			if ($this->sortcol !== "") $tb2 = data::sort($tb2, $this->sortcol, SORT_DESC);
			
			if ($this->filter !== "") {
				$tb2 = data::lfn($tb2);
				$tb2 = data::filter($tb2, $this->filter); 
				unset($tb2["lfn"]);
			}
			$ret = $tb2;
			
			$tb2 = data::get($tb2, "var_cox,or_cox,lci_cox,uci_cox,p_cox,or_log,lci_log,uci_log,p_log");
			$tb2 = data::filter($tb2, "@var_cox@ !== ''");
			$tb2 = data::fu($tb2, "^p_|^or_|ci_", "format2(@, '0.00')");
			$tb2 = data::comp($tb2, "@var_cox@ = label::v(@var_cox@);");
			$tb2 = data::comp($tb2, "if (@p_cox@ < .05) {@var_cox@ = '<b>'.@var_cox@.'</b>'; @p_cox@ = '<b>'.@p_cox@.'</b>'; }");
			$tb2 = data::comp($tb2, "if (@p_log@ < .05) {@var_cox@ = '<b>'.@var_cox@.'</b>'; @p_log@ = '<b>'.@p_log@.'</b>'; }");
			#show3($tb2); return;
			
			$tb3 = flip3($tb2);
			$tb3[0] = preg_replace("/su_[01]_(.+)/", "\\1.", $tb3[0]);
			$tb3[0] = preg_replace("/_cox|_log/", "", $tb3[0]);
			$tb3[0] = preg_replace("/".$tod_log."_/", "", $tb3[0]);
			$tb3[0] = preg_replace(array("/pm2_(.+)/", "/sum2_(.+)/", "/count2_(.+)/"), array("%", "Fälle", "n"), $tb3[0]);
			$tb3[0][0] = "";
		}
		$s = new show();
		$s->cols = $this->cols;
		$s->borders = "^0$:.:s_o s_u,^last$:.:s_u s_o,2 to lastbut1:.:d_o,.:^[48]$|^1[148]$:s_r";
		$o = " class = s_o ";
		$s->firstrow = "<tr><td class = s_o></td>
					<td ".$o.cs(4).">Cox Regression<br>(Überleben insgesamt)</td>
					<td ".$o.cs(4).">Logistische Regression<br>(30-Tages-Überleben)</td>
				</tr>"; 
		$s->fe0($tb3);

		$tb3 = rowinsert($tb3, 0);
		$tb3[0] = seq("vc1-vc7");
		$tb4 = flipn($tb3);
		
		$tb4 = data::fu($tb4, ".", "preg_replace('/\<b\>|\<\/b\>/', '', @)");
		writexls6($tb4, $ofi.".xls", $cols = "35,8", $pos = "1,3", $fo = "@,0.00");
		return data::get($ret, "var_cox,or_,p_,lci_,uci_");
	}	
	
	/*            n    %
	      A
	      0      10   50
	      1      10   50
	      total  20  100
	      B
	      0      20   50
	      1      20   50
	      total  40  100
	*/
	function uvstapel($fe, $uv){
		$l = chr(10);
		if ($this->name == "" ) $ofi = ofi(currdb(), $uv."_uvstapel"); else $ofi = $ofi = "/eigenes/www/".currdb()."/out/".$this->name;
		$uvf = data::vl($fe, $uv);
		$avf = data::vl($fe, $av); $av = implode(",", $avf);
		$fuf = explode(",", $fu);
		$fe = data::comp($fe, "@alle@ = 1;");
		if ($this->neu == 1) {
			for ($i = 0; $i < count($uvf); ++$i){
				$u = $uvf[$i];
				$o = data::agg($fe, "alle", "^$u$", "count2");
				$o = data::comp($o, "@$u@ = givelabel('$u', @$u@);");
				$o = data::rename($o, "/^alle_/", "");
				$o = data::rename($o, "/^$u$/", "codes");
				$o = data::comp($o, "@pr@ = round(@count2@/".sum2($o["count2"])." * 100, 0);");
				
				$gr = count($o["codes"]);
				$o = data::rowinsert($o, $gr + 1);
				$o["count2"][$gr] = sum2($o["count2"]); 
				$o["codes" ][$gr] = "total";
				$o["pr"    ][$gr] = 100;
				
				$o = data::comp($o, "@var@ = '$u';");
				$o = data::rowinsert($o, 0); $o["codes"][0] = "<b>".givelabel($u)."</b>";
				$o2[] = $o;
			}
			$o2 = data::add($o2);
			$o2 = data::get($o2, "codes,count2,pr");
			$o2 = data::rename($o2, "/^codes$/", "Indikator");
			$o2 = data::rename($o2, "/^count2$/", "n");
			$o2 = data::rename($o2, "/^pr$/", "%");
			write::fe($o2, $ofi);
			$s = new show();
			$s->cols = $this->cols;
			$s->save = $ofi;
// 			echop($this->ti->nr("Listing <font color = ".$this->color.">[".$covs.", ".fromto($ofi, "/out/", "")."]</font>", $fe));
			$s->cols = "550,50";
			$s->fe($o2);
			#$o3 = $o2; $o3["Indikator"] = preg_replace("/\<\/?b\>/", "", $o3["Indikator"]);
			writexls6($o2, $ofi.".xls", $cols = "20,10,10,10,10,12,10", $pos = "1,3", $fo = "@,0,0");
			return $o2;
		} else {
			$fe = flipn(read2d($ofi));
			echop($this->ti->nr("Listing <font color = ".$this->color.">[".$covs.", ".fromto($ofi, "/out/", "")."]</font>", $fe));
			echop(read2($ofi.".html"));
		}
	}	

	/*                av1                av2       av3
	        av1_pm sum percent count  
	uv1  1
	     2
	uv2  1
	     2
	uv3  1
	     2
	uvstapel_by_av($fe, "age" "gr1,gr2", "mean2,count2");
	*/
	function uvstapel_by_av($fe, $av, $uv, $fu = "count2,percent2,pm2,sum2") {
		$l = chr(10); $mi = "9999.99";
		$fe = data::get($fe, $av.",".$uv);
		if ($this->name == "" ) $ofi = ofi(currdb(), $uv."_uvstapel_by_uv"); else $ofi = $ofi = "/eigenes/www/".currdb()."/out/".$this->name;
		$uvf = data::vl($fe, $uv);
		$avf = data::vl($fe, $av); $av = implode(",", $avf);
		$fuf = explode(",", $fu);
		$fe = data::comp($fe, "@alle@ = 1;");
		
		for ($i = 0; $i < count($uvf); ++$i){
			$u = $uvf[$i];
			#md($u);
			#md("o");
			$o = data::agg($fe, $av, "^$u$", $fu);
			$o = data::comp($o, "@gr@ = label::c('".$u."', @$u@);"); unset($o[$u]);
			#show3($o);
	
			#md("m1");
			$m1 = data::agg(data::filter($fe, "@$u@ == '' and @$u@ !== '0'"), $av, $u, $fu, 0);  $m1 = data::comp($m1, "@gr@ = '. (uv)';"); unset($m1[$u]);
			#show3($m1);
			#md("count(m1) = ".count($m1));
			#print_r($m1);
			
			$mi2 = data::filter($fe, "@$av@ === ''");
			$mi2 = data::comp($mi2, "@$av@ = 1;");
			
			md("m2");
			$fem2 = $fe;
			for ($j = 0; $j < count($avf); ++$j){$a = $avf[$j]; $fem2 = data::comp($fem2, "if (@$a@ === '') @$a@ = 1; else @$a@ = 0;");}
			$m2 = data::agg($fem2, $av, "alle", $fu); $m2 = data::comp($m2, "@gr@ = '. (av)';"); unset($m2[$u]); unset($m2["alle"]);
			for ($j = 0; $j < count($avf); ++$j){$a = $avf[$j]; $m2 = data::comp($m2, "@".$a."_count2@ = @".$a."_sum2@; @".$a."_sum2@ = ''; @".$a."_pm2@ = '';");}
			
			show3($m2); 
			#md("count(m2) = ".count($m2));
			#print_r($m2);
			
			#md("g");
			$gfe = $fe;
			for ($j = 0; $j < count($avf); ++$j){$a = $avf[$j]; $gfe = data::comp($gfe, "@$a@ = 1;");}
			$g = data::agg($gfe, $av, "^alle$", $fu); $g = data::comp($g, "@gr@ = 'total';"); unset($g["alle"]);
			for ($j = 0; $j < count($avf); ++$j){$a = $avf[$j]; $g = data::comp($g, "@".$a."_pm2@ = ''; @".$a."_sum2@ = ''; @".$a."_percent2@ = '100.0';"); }
			#show3($g); 
			
			for ($j = 0; $j < count($avf); ++$j){
				$a = $avf[$j];
				if (preg_match("/percent2/", $fu)) {
					$c = $a."_count2"; 
					$su = sum2($o[$c]); $su2 = sum2($o[$a."_sum2"]);  
					$o = data::comp($o, "@".$a."_percent2@ = @$c@ / $su * 100;");
				}
				$m2 = data::comp($m2, "@".$a."_pm2@ = ''; @".$a."_sum2@ = '';");
				$o2[$i] = $o;
			}
			
			
			$o2[$i] = data::rowinsert($o, 0); $o2[$i]["gr"][0] = label::v($u);
			$st[$i] = $st[$i - 1] + count(data::uvstufen($fe,$u)) + 2;
			
			if (count($m1) == count($avf) * count($fuf) + 1) {$o2[$i] = data::add(array($o2[$i], $m1)); ++$st[$i];}
			if (count($m2) == count($avf) * count($fuf) + 1) {$o2[$i] = data::add(array($o2[$i], $m2));  ++$st[$i];}
			$o2[$i] = data::add(array($o2[$i], $g));
			
			$o2[$i] = data::get($o2[$i], "gr$,".$av);
			#md("o2");
			#show3($o2[$i]); return;
		}
		#return;
		
		$o3 = data::add($o2);
		$o3 = data::fu($o3, "mean2|sd2|median2", "format2(@, '0.00')");
		$o3 = data::fu($o3, "pm2|percent2", "format2(@, '0.0')");
			
		$o4 = flip3($o3);
		$o4[0] = preg_replace(array("/(.+)_count2/", "/(.+)_percent2/", "/(.+)_pm2/", "/(.+)_sum2/", "/^gr$/"), array("n", "%", "Rate", "Fälle", ""), $o4[0]);
		
		
		$s = new show();
		$s->borders = "^0$|".fins($st, "|", "^", "$").":.:s_u ";
		for ($j = 1; $j < count($avf); ++$j) {$b += count($fuf); $s->borders .= ",.:^$b$:s_r";}
		
		$t = "<tr><td class = s_o colspan = 1></td>";
		for ($j = 0; $j < count($avf); ++$j) $t .= "<td align = center class = s_o colspan = ".count($fuf).">".label::v($avf[$j])."</td>";
		$t .= "</tr>";
		$s->firstrow = $t;
		$s->cols = $this->cols;
		$s->fe0($o4);
		
		$fe = $o3;
		$spreadsheet = new Spreadsheet();
		$kf = array_keys($fe); $c1 = $kf[0]; $gr1 = count($fe[$kf[0]]); $gr2 = count($kf);
		$spreadsheet->getDefaultStyle()->getFont()->setName('Arial') ->setSize(10);
		$spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true);
		$a = $spreadsheet->setActiveSheetIndex(0);
		$a->setShowGridlines(0);
	
		for ($i = 0; $i < $gr1; ++$i) {
			for ($j = 0; $j < $gr2; ++$j){
				if ($i == 0) $a->setCellValue(co($j).($i + 3), givelabel($kf[$j]));
				$w = $fe[$kf[$j]][$i];
				$a->setCellValue(chr(65 + $j).($i + 3), $w);
			}
		}
		
		$fo = "@,@,0,0.0,0.0,0"; $fof = explode(",", $fo);
		for ($j = 0; $j < $gr2; ++$j){
			if ($j < count($fof)) $f = $fof[$j]; else $f = $fof[count($fof) - 1];
			$c = co($j)."3:".co($j).($gr1 + 1);
			$a->getStyle($c)->getNumberFormat()->setFormatCode($f);
		}
		
		$a->setCellValue("C1", "Rate"); $rg = "C1:F1"; $a->mergeCells($rg); $a->getStyle($rg)->getBorders()->getBottom()->setBorderStyle("thin");
		$a->getStyle($rg)->getAlignment() ->setHorizontal("center") ->setVertical("center");
		$a->getStyle("A2:F2")->getBorders()->getBottom()->setBorderStyle("thin");
		$a->getRowDimension(1)->setRowHeight(20); $a->getRowDimension(2)->setRowHeight(15);
		
		for ($j = 0; $j < count($fuf); ++$j) $a->setCellValue(co(2 + $j)."2", label::v($fuf[$j]));
		
		for ($j = 0; $j < count($st); ++$j){
			$r = $st[$j] + 2;
			$a->getStyle("A$r:F$r")->getBorders()->getBottom()->setBorderStyle("thin");
			$a->getStyle("A$r:F$r")->getBorders()->getTop()->setBorderStyle("thin");
			
		}

		$a->mergeCells("A3:F3");
		for ($j = 0; $j < count($st) - 1; ++$j) {$r = $st[$j] + 3; $a->mergeCells("A$r:F$r");}
		
		$writer = new Xlsx($spreadsheet);
		$writer->save($ofi.".xls");
		exec("chmod 666 '$ofi.xls'");
		
		#xls::show($ofi.".xls");
		return $o3;
	}
	
	/*          Gruppe 1                 Gruppe 2
		        pm2   sum2  count2   pm2    n    count2
	      Var A    5    10      50      5    10    50
	      Var B    0     5      10      0     5    10
	*/
	function uvstapel2($fe, $uv, $av, $fu = "pm2,sum2,count2"){
		$l = chr(10);
		$uvf = data::vl($fe, $uv);
		$avf = data::vl($fe, $av); $av = implode(",", $avf);
		$fuf = explode(",", $fu);
		
		$ofi = ofi(currdb(), $this->name == "" ? fins($uvf)."_uvstapel" : $this->name);
		
		if ($this->neu == 1) {
			for ($i = 0; $i < count($uvf); ++$i){
				$u = $uvf[$i];
				$o = data::agg($fe, $av, $uvf[$i]."$", $fu);
				$o = data::comp($o, "@var@ = '$u';");
				$o = data::rename($o, "/^$u$/", "codes");
				$o = data::get($o, "var,codes,".$fu);
				$o = data::casestovars($o, "var", "codes", "_");
				$o = data::comp($o, "@var@ = @var@.' '.label::v(@var@);");
				$o2[] = $o;
			}
			$o2 = data::add($o2);
			$o2 = data::get($o2, "var,_1,_0");
			$o2 = data::rename($o2, "/^var$/", " ");
			$o2 = data::sort($o2, "v63_pm2_1", "SORT_DESC"); $o2 = self::array_values($o2);
			$o2 = data::fu($o2, "_pm2", "format2(@, '0.0')");
			
			write::fe($o2, $ofi);
			
			$o3 = flip3($o2);
			$o3[0] = preg_replace(array("/(.+)pm2_(.+)/", "/(.+)sum2_(.+)/", "/(.+)count2_(.+)/"), array("%", "Fälle", "n"), $o3[0]);
			$c = " class = s_o ";
			$s = new show();
			$s->borders = "^0$:.:s_o s_u,^last$:.:s_u s_o,2 to lastbut1:.:d_o,.:^[03]$:s_r";
			$s->firstrow = "<tr><td class = s_o></td>
						<td ".$c.cs(3).">30-Tages-Sterberate<br>der Zielgruppe</td>
						<td ".$c.cs(3).">Rate der<br>übrigen Patienten</td>
					</tr>"; 
			$s->cols = $this->cols;
			echop($this->ti->nr("Übersicht <font color = ".$this->color.">[".$av."]</font>", $fe));
			$s->fe0($o3);
			#$s->fe($o2);
			#writexls4($o2, $ofi.".xls", $cols = "20,10,10,10,10,12,10", $pos = "1,2", $fo = "@,0.0,0,0,0.0,0,0"); #showxls($ofi.".xls");
			return $o2;
		} else {
			$fe = flipn(read2d($ofi));
			echop($this->ti->nr("Listing <font color = ".$this->color.">[".$covs.", ".fromto($ofi, "/out/", "")."]</font>", $fe));
			echop(read2($ofi.".html"));

		}
		
	}
	
	function corr1vs2($fe, $av1, $av2, $me = 2){
		$l = chr(10);
		$db = currdb();
		$ofi = ofi(currdb(), $this->name == "" ? "corr_".fins($av1)."_by_".fins($av2) : $this->name);
		
		$fav1 = data::vl($fe, $av1); $av1  = implode(",", $fav1);
		$fav2 = data::vl($fe, $av2); $av2  = implode(",", $fav2);
		
		if ($this->neu == 1) {
			if ($me == 1) $me = "pearson"; else $me = "spearman";
			
			$f = "/eigenes/downs/temp/corr.dat"; export::asc(data::get($fe, $av1.",".$av2), $f);
			$r .= "x <- read.table('$f', header = TRUE, stringsAsFactors = FALSE); x[] <- lapply(x, as.numeric);".$l;
			
			for ($i = 0; $i < count($fav2); ++$i){
				$a1 = $fav1[0]; $a2 = $fav2[$i];
				$r .= "x2 <- x[, c('$a1', '$a2')];
					x2 = subset(x2, complete.cases(x2)); n <- nrow(x2);
					colnames(x2) <- c('$a1', '$a2');
					c <- cor.test(x2\$$a1, x2\$$a2, method = '$me');
					y <- data.frame(cbind('$a1', '$a2', c\$estimate, c\$p.value, n ));
					colnames(y) <- c('corr_av1', 'corr_av2', 'corr', 'p', 'n');".$l;
					if ($i == 0) $r .= "tmpadd <- y; ".$l;
					if ($i >  0) $r .= "tmpadd <- rbind(tmpadd, y); ".$l;
			}
			$r .= "write.table(tmpadd, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); rm(list = ls());".$l;
			$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); exec("sudo Rscript '$fi'");
		}
		$fe = read3d($ofi);
		
		$fe = data::comp($fe, "@vs@ = 'vs.';");
		$fe = data::filter($fe, "is_numeric(@p@)");
		$fe = data::get($fe, "corr_av1,vs,corr_av2,corr,p,n");
		
		$fe = data::fu($fe, "^corr|^p", "format2(@, '0.0000')");
		$fe = data::comp($fe, "if (@corr@ == 'NA') @corr@ = '-';");
		$fe = data::comp($fe, "if (@p@    == 'NA') @p@    = '-';");
		$fe = data::comp($fe, "@corr_av1@ = givelabel(@corr_av1@);");
		$fe = data::comp($fe, "@corr_av2@ = givelabel(@corr_av2@);");
		$m = new export; $m->type = "text"; $m->mysql($fe, $this->name);
		$fe = data::comp($fe, "if (is_numeric(@p@) and @p@ <= 0.05) @p@ = '<b>'.@p@.'</b>';");
		$fe = labelheaders3($fe);
		$g = givelabel($av1); tb($g.": Korrelation nach ".ucfirst($me).", p = epxlor. Signifikanztest für den Korrelationskoeffizienten (explorativ signifikant wenn p <u><</u> 0.05 ).");
		writexls6($fe, $ofi.".xls", $cols = "20,10,10,10,10,12,10", $pos = "1,3", $fo = "@,@,0.000", "");
		show3($fe, "1,2,1,2", "200,50,200,80");
	}
	
	// $t->factor($fe, "a31_");
	// www.personality-project.org/r/html/principal.html
	function factor($fe, $av, $fo = "0.00", $factorzahl = 3, $short = 1){
		$l = chr(10);
		$db = currdb();
		$ofi = ofi(currdb(), $this->name == "" ? "fa_".fins($av) : $this->name);
		
		$av_ = data::vl($fe, $av); $avz = count($avf);
		
		if ($this->neu == 1) {
			$f = "/eigenes/downs/temp/fa.dat"; export::asc(data::get($fe, $av), $f);
			$r .= "x <- read.table('$f', header = TRUE, stringsAsFactors = FALSE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
			$r .= "library(psych); fit <- principal(x, nfactors = $factorzahl, rotate = 'varimax');".$l;
		
			$r .= "p <- print(fit);".$l;
			$r .= "lo <- cbind(row.names(fit\$loadings), fit\$loadings);".$l;
			$r .= "ss <- cbind(row.names(p\$Vaccounted), p\$Vaccounted);".$l;
		
			$r .= "d <- rbind(lo, ss);".$l;
			$r .= "colnames(d)[1] <- 'vars';".$l;
			
			$r .= "write.table(d, file = '$ofi', sep = '\\t', quote = FALSE, row.names = FALSE); ".$l;
			$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); exec("sudo Rscript '$fi'");
		}

		$fe = read3d($ofi);
		$fe = data::fu($fe, "^RC", "format2(@, '0.000')");
		$fe = data::filter($fe, "@vars@ !== ''");
		$fe = data::rename($fe, array("/vars/"), array(label::set($av_[0])));
		$fe = label::col0($fe);
		$fe = label::headers($fe);
		show3($fe);
	}
	
	//function mytab
	//      roc  lo_roc  up_roc
	// av1
	// av2
	// av3
	// av4
	//roc(1, $tb, "gr", "age");
	function roc($neucalc, $fe, $uv, $av, $fo = "0.00"){ //ROC für mehrere av nach unten gestapelt, nur 1 uv angeben, http://cran.r-project.org/web/packages/pROC/README.html
		$db = currdb();
		$l = chr(10);
		$ofi = ofi($db, "roc_".$tb."_".$uv."_".$av);
		if ($neucalc == 0) { show(read2d($ofi)); return;}
		
		$uvf = vl($fe[0], $uv); $uv = implode(",", $uvf);
		$avf = vl($fe[0], $av); $av = implode(",", $avf); $avgr = count($avf);
		$fe = getmat2($fe, $uv.",".$av);
		$f = "/tmp/tmp.dat"; writefe($fe, $f, 1);
		$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
		$r .= "library(pROC);".$l;
		for ($j = 0; $j < $avgr; ++$j){
			$a = $avf[$j];
			$r .= "r1 <- roc(x\$$a ~ x\$$uv); r2 <- ci(r1);".$l;
			$r .= "y <- data.frame(cbind('$a', r2[2], r2[1], r2[3]));".$l;
			$r .= "colnames(y) <- c('av', 'roc', 'rocl', 'rocu');".$l;
			if ($j == 0) $r .= "y2 <- y; ".$l;
			if ($j >  0) $r .= "y2 <- rbind(y2, y); ".$l;
		}
		$o = "/tmp/roc.txt";
		if (file_exists($o)) unlink ($o);	
		$r .= "write.table(y2, file = '$o', sep = '\\t', quote = F, row.names = FALSE); ".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);

		$fe = read2d($o);
		$fe = selif3($fe, "@av@ <> ''");
		
		$fe = function_on_fe($fe, "^r", "format2(@, '0.000' )");
		$fe = function_on_fe($fe, "^p", "format2(@, '0.0000')");
		
		comp($fe, "@av@ = givelabel(@av@);");
		$fe = labelheaders($fe); $fe[0][0] = "";
		writefe($fe, $ofi);
		show($fe);
	}
	
	function lca1($fe, $classes, $groupname = 'predclass', $lca = "lca", $ofi = '/eigenes/downs/temp/lca.grp', $fepred){
		$l = chr(10);
		$db = currdb();

		$r .= "source('/mnt/69/eigenes/commandr/functions.r');".$l;
		$z = str::zufall(3);
		$f1 = "/eigenes/downs/temp/".$lca.$z.".dat"; export::asc($fe, $f1);
		$f2 = "/eigenes/downs/temp/".$lca.$z.".predfe.dat"; export::asc($fepred, $f2);
		
		$r .= "x <- read.table('$f1', header = T, stringsAsFactors = F); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
		$r .= "y <- read.table('$f2', header = T, stringsAsFactors = F); y[] <- lapply(y, as.numeric); y <- subset(y, complete.cases(y));".$l;
		
		$r .= "x = lca1(x, gr = $classes, na = '$groupname', '$lca', '$ofi', '$db', y);".$l;
		
		$r .= "rm(x);".$l;
		$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
		
		show3(read3d($ofi.".coeff"));
		
		$o = import::asc($ofi);
		return data::get($o, "id,^$groupname$");
	}
	
}

class data {

	function get(&$fe, $v){
		$vf = self::vl($fe, $v);
		for ($j = 0; $j < count($vf); ++$j) {$v = $vf[$j]; if (array_key_exists($v, $fe)) $o[$v] = $fe[$v];}
		return $o;
	}
	
	// $fe = data::comp($fe, "is_even(@c1@) ? @gr@ = 1 : @gr@ = 0; @c33@ = implode('.', array(@c3@, @c4@,@c5@)); @c44@ = explode('.', @c33@)[0]; "); 
	// $fe = data::get($fe, "if (in_array(@x@, array(1, 2, 3))) @gr@ = 1; else @gr@ = 0;");
	// $fe = data::comp($fe, "@cmean@ = mean2(array(".fins(data::vl($fe, "^c[12]$"), ",", "@", "@")."));");
	// $v1 = "^v0[2-9]$,^v1[0-8]$"; $v2 = data::vls($fe, $v); md($v2); $fe = data::comp($fe, "@ipos@ = sum2(array($v2)); "); 
	// ____ median-split ____
	// $v = "v1"; $fe = data::comp($fe, "if (@$v@ !== '') {if (@$v@ < ".median2($fe[$v]).") @k$v@ = 0; else @k$v@ = 1;}");
	
	function comp($fe, $c){
		$kf = array_keys($fe);
		preg_match_all("/(@.*?@)/", $c, $ma);
		
		for ($j = 0; $j < count($ma[0]); ++$j){
			$m = $ma[0][$j]; 
			$m = preg_replace("/@/", "", $m);
			if (!in_array($m, $kf)) $fe[$m] = array_fill_keys(array_keys($fe[$kf[0]]), null);
		}
		eval("while (list(\$k, \$w) = each(\$fe['$kf[0]'])) { ".preg_replace("/@(.*?)@/", "\$fe['\$1'][\$k]", $c). "}");
		return $fe;
	}
	
	// $fe = data::rnd(50, 4, 1, 5); show3(freqraw($fe, "c02"));
	function freq($fe, $u){
		$fe = data::get($fe, $u);
		for ($i = 0; $i < count($fe[$u]); ++$i){
			$w = $fe[$u][$i];
			$o["u"][$w] = $w;
			$o["n"][$w] = ++$o["n"][$w];
		}
		$o["u"] = array_values($o["u"]);
		$o["n"] = array_values($o["n"]);
		$o = data::filter($o, "@u@ !==''");
		$s = sum2($o["n"]); if ($s == "") $s = 0;
		$o = data::comp($o, "if ($s !== 0) @col@ = format2(@n@ / $s * 100, '0').' %';");
		return $o;
	}
	
	function head($fe, $n = 20){
		$l = "lfn_tmp";
		$fe = data::lfn($fe, $l);
		$fe = data::filter($fe, "@$l@ <= $n");
		unset($fe[$l]);
		return $fe;
	}
	
	// repeatarray, arrayrepeat
	function reparr($a, $n){ 
		return array_merge(...array_fill(0, $n, $a));
	}
	
	// $fe = data::split3($fe, "sex");
	function split3($fe, $v){
		$st = data::uvstufen_neu($fe[$v]);
		for($j = 0; $j < count($st); ++$j) {
			$n = $v."_".$st[$j]; $w = $st[$j];
			$fe = data::comp($fe, "if (@$v@  <> '') {if (@$v@  == '$w') @$n@ = 1; else @$n@ = '0'; }");
		}
		return $fe;
	}	

	//show(data::rnd(10, 5));
	function rnd($r, $c, $lo = 0, $up = 100, $fo = "00"){
		for ($j = 1; $j <= $c; ++$j) {
			$j = format2($j, $fo);
			for ($i = 0; $i < $r; ++$i) $o["c".$j][$i] = mt_rand($lo, $up);
		}
		return self::lfn($o);
	}
	
	function lfn($fe, $n = "lfn"){
		$kf = array_keys($fe);
		$fe[$n] = $fe[$kf[0]];
		while (list($k, $w) = each($fe[$n])) $fe[$n][$k] = $k + 1;
		return $fe;
	}
	
	function vl(&$fe, $v, $tx = ""){
		$vf = explode(",", $v);
		for ($j = 0; $j < count($vf); ++$j){
			while (list($k, $zei) = each($fe)) if (preg_match("/".$vf[$j]."/", $k)) $o[] = $tx.$k.$tx;
			reset($fe);
		}
		return $o;
	}
	
	function vls($fe, $v, $tx = ",", $pre = "@", $post = "@"){
		return fins(self::vl($fe, $v), $tx, $pre, $post);
	}
	
	// array_multisort($fe['lfn'],4);
	// $fe = data::sort($fe, "zeit");
	function sort($fe, $v, $asc = SORT_ASC){
		$vf = self::vl($fe, $v);
		while (list($k, $w) = each($fe)) if (!in_array($k, $vf)) $vf[] = $k;
		#md(fins($vf));
		$so = "\$fe['".implode("'],$asc,\$fe['", $vf)."'],$asc";
		#md($so);
		eval("array_multisort($so);");
		#md("array_multisort($so);");
		return $fe;
	}
	
	// $fe = data::filter($fe, "is_numeric(@v1@)");
	// $fe = data::filter($fe, "in_array(@id@, array(2,7))");
	// $fe = data::filter($fe, "!(@v01@ == 382 or @v01@ == 100)");
	// $fe = data::filter($fe, "preg_match('/[0-9a-z]/i', @v1@)");
	// $fe = data::filter($fe, "!preg_match('/\.srt|\.sub/', @film@)");
	
	function filter($fe, $c){
		$v = "_tmp_";
		$fe = self::comp($fe, "if ($c) @$v@ = 1;");
		$kf = array_keys($fe);
		$i2 = -1;
		for ($i = 0; $i < count($fe[$v]); ++$i) if ($fe[$v][$i] == 1) {
			++$i2; 
			for ($j = 0; $j < count($kf); ++$j) {$k = $kf[$j]; if ($k !== $v) $o[$k][$i2] = $fe[$k][$i];}
		}
		return $o;
	}
	
	// $fe = data::rnd(10, 5); $fe = data::function_on_fe($fe, "^c", "preg_replace('/[1-5]/', '', @)");
	// $fe = data::function_on_fe($fe, "^c([1-5])", "trunc2(@ / 3)");
	function function_on_fe($fe, $v, $fu){
		$vf = self::vl($fe, $v, "@");
		for ($j = 0; $j < count($vf); ++$j) {
			#if ($j == 0) md($vf[$j]." = ".preg_replace("/@/", $vf[$j], $fu).";");
			
			$fe = self::comp($fe, $vf[$j]." = ".preg_replace("/@/", $vf[$j], $fu).";");
		}
		return $fe;
	}
	
	// $fe = data::fu($fe, "mean|sd", "format2(@, '0.00')");
	// $fe = data::fu($fe, "^c([1-5])", "trunc2(@ / 3)");
	function fu($fe, $v, $fu){return self::function_on_fe($fe, $v, $fu);}
	
	// __ mit recode ____
	// $fe = data::recode($fe, "v04", "array('/m/', '/w/')", "array(1, 2)");
	// $fe = data::recode($fe, "gruppe", "array('/Nicht.+/', '/.+C81.+/', '/.+sonstige.+/')", "array(0, 1, 2)");
	// $fe = data::rnd(10, 5); $fe = data::recode($fe, "^c|^l", "array('/5/')", "array('_')");
	// ____ median-split ____
	// $v = "v1"; $fe = data::comp($fe, "if (@$v@ !== '') {if (@$v@ < ".median2($fe[$v]).") @k$v@ = 0; else @k$v@ = 1;}");
	// ___ mit switch ____
	// $fe = data::comp($fe, "switch (1){case (@$v@ >=  0 and @$v@ <=  5): @$o@ = 1; break;};");
	// $v = "zeit"; $o = $v."k"; $fe = data::comp($fe, "switch (1){
	// 	case (@$v@ >=   0 and @$v@ <=  15): @$o@ = 0; break;
	// 	case (@$v@ >   15 and @$v@ <=  45): @$o@ = 1; break;
	// 	case (@$v@ >   45 and @$v@ <=  75): @$o@ = 2; break;
	// 	case (@$v@ >   75 and @$v@ <= 105): @$o@ = 3; break;
	// 	case (@$v@ >  105 and @$v@ <= 135): @$o@ = 4; break;
	// 	case (@$v@ >  135 and @$v@ <= 165): @$o@ = 5; break;
	// 	case (@$v@ >  165 and @$v@ <= 195): @$o@ = 6; break;
	// 	case (@$v@ >  195 and @$v@ <= 225): @$o@ = 7; break;
	// };");
	// ___ mit preg _____
	// $fe = function_on_fe($fe, "^v3[7-9]$", "preg_replace(array('/^[\s]*$/'), array('0'), '@')"); #empty string wird zu Null
	function recode($fe, $v, $alt, $neu){
		return self::fu($fe, $v, "preg_replace($alt, $neu, @)");
	}
	
	// $fe = data::rename($fe, array("/Ausgegeben wo/", "/Wofür/"), array("Wo", "Wofuer"));
	// $fe = data::rnd(10, 5); $fe = data::rename($fe, "/^c([2-4])/", "kk\$1");
	// $fe = data::rename($fe, array("/(aa)/"), array("<b>\$1</b>"));
	// $fe['newname'] = $fe['oldname']; unset($fe['oldname']);
	// $kf = array_keys($fe); for ($j = 1; $j <= count($kf); ++$j) $o["v".sprintf('%02d', $j)] = $fe[$kf[$j - 1]]; $fe = $o;
	function rename($fe, $alt, $neu){
		return array_combine(preg_replace($alt, $neu, array_keys($fe)), array_values($fe));
	}
	
	function uvstufen($fe, $v, $ohneleer = 1){
		$st = array_unique($fe[$v]); sort($st);
		if ($ohneleer == 1) {for ($i = 0; $i < count($st); ++$i) if (trim($st[$i]) !== "") $o[] = $st[$i];} else $o = $st;
		return $o;
	}
	
	// $fe = data::rnd(15000, 55); $fe = data::comp($fe, "is_even(@c01@) ? @gr@ = 1 : @gr@ = 2;"); $fe = data::lfn($fe);
	// $st = time::start(); md("uv = ".fins(data::uvstufen_neu($fe["gr"]))); time::end($st, "uvstufen_neu ...");
	// $st = time::start(); md("uv = ".fins(data::uvstufen($fe, "gr")));     time::end($st, "altes uvstufen ...");

	// md("uv = ".fins(data::uvstufen_neu($fe["gr"])));
	function uvstufen_neu($fe){
		$o = array();
		for ($i = 0; $i < count($fe); ++$i) if (trim($fe[$i]) !== "") {if (!in_array($fe[$i], $o)) $o[count($o)] = $fe[$i];}
		sort($o);
		return $o;
	}
	
	// $fe = data::indicator($fe, "v1");
	function indicator($fe, $v, $tx = "_"){ //dummy
		$u = data::uvstufen($fe, $v);
		for ($j = 0; $j < count($u); ++$j) $fe = data::comp($fe, "if (@$v@ == '".$u[$j]."') @".$v.$tx.$u[$j]."@ = 1; else if (@$v@ !== '') @".$v.$tx.$u[$j]."@ = 0;");
		return $fe;
	}
	
	// $fe1 = data::rnd(15, 2); $fe1 = data::rename($fe1, "/^c0([0-9])/", "kk\$1"); show3($fe1); 
	// $fe2 = data::rnd(15, 2); show3($fe2); 
	// $fe12 = data::merge(array($fe1, $fe2), "lfn"); show3($fe12); 
	function merge($fearr, $v){
		for ($i = 0; $i < count($fearr); ++$i) {
			$i == 0 ? $fe = $fearr[0] : $fe = self::merge_base($fe, $fearr[$i], $v);
		}
		return $fe;
	}
	
	function merge_base($fe1, $fe2, $v){
		$t = "tmpkey";
		$kf1 = self::vl($fe1, $v); $fe1 = data::comp($fe1, "@".$t."1@ = 'k.'.@".implode("@.'.'.@", $kf1)."@;");
		$kf2 = self::vl($fe2, $v); $fe2 = data::comp($fe2, "@".$t."2@ = 'k.'.@".implode("@.'.'.@", $kf2)."@;");
		#print_r($kf1);
		
		#md($v."_____________ in merge ____________");
		#show3($fe1);
		#show3($fe2);
		
		$tk0 = array_unique(array_merge($fe1[$t."1"], $fe2[$t."2"])); $tk0 = array_values($tk0);
		foreach ($fe1[$t."1"] as $k => $v) $tk1[$v] = $k;
		foreach ($fe2[$t."2"] as $k => $v) $tk2[$v] = $k;
		
		foreach ($tk0 as $k => $v) {
			$i0 = $k;
			$i1 = $tk1[$v];
			$i2 = $tk2[$v];
			#$fe[$t."0"][$i0] = $v;

			foreach($kf1 as $k1 => $v1) $fe[$v1][$i0] = max($fe1[$v1][$i1]."", $fe2[$v1][$i2]."");
			
			foreach ($fe1 as $k2 => $v2) if ($k2 !== $t."1" and !in_array($k2, $kf1)) $fe[$k2][$i0] = $fe1[$k2][$i1];
			foreach ($fe2 as $k2 => $v2) if ($k2 !== $t."2" and !in_array($k2, $kf2)) $fe[$k2][$i0] = $fe2[$k2][$i2];
		}
		
		#show3($fe);
		return $fe;
	}
	
	//$gr = 10;
	//for ($i = 0; $i < $gr; ++$i) $fe["zeit1"][$i] = mt_rand( 1, 100);
	//for ($i = 0; $i < $gr; ++$i) $fe["zeit2"][$i] = mt_rand( 1, 100);
	//for ($i = 0; $i < $gr; ++$i) $fe["code0"][$i] = mt_rand(97, 100);
	//show3($fe);
	//$fe = data::agg($fe, "code0", "zeit", "max2,sum2");
	
	# ähnlich wie SPSS aggregate /break id /m1 to m5 = mean(v1 to v5) /sd1 to sd5 = sd(v1 to v5).
	function agg($fe, $av, $uv, $fu, $ohneleer = 1){
		$uvf = self::vl($fe, $uv); $ugr = count($uvf);
		$avf = self::vl($fe, $av); $avz = count($avf);
		$fuf = explode(",", $fu);
		if ($ugr > 1) {$t = "tmp"; $uv = $t; $fe = self::comp($fe, "@tmp@ = ';'.@".implode("@.';'.@", $uvf)."@.';' ;"); } else $uv = $uvf[0];
		$fe = self::get($fe, $uv.",".$av);
		$st = data::uvstufen($fe, $uv, $ohneleer);
		#md(fins($st));
		for ($s = 0; $s < count($st); ++$s){
			$u = $st[$s];
			$fe2 = self::filter($fe, "@$uv@ == '$u'");
			$o[$uv][$s] = $u;
			for ($j = 0; $j < $avz; ++$j){
				$a = $avf[$j];
				for ($f = 0; $f < count($fuf); ++$f) $o[$a."_".$fuf[$f]][$s] = call_user_func_array($fuf[$f], get3($fe2, $a));
			}
		}
		
		if ($ugr > 1) for ($j = 0; $j < $ugr; ++$j) $o = self::comp($o, "@".$uvf[$j]."@ = explode(';', @".$t."@)[".($j + 1)."];");
		$u = fins($uvf, ",", "^", "$");
		$a = fins($avf, ",", "^", "");
		$o = self::get($o, $u.",".$a);
		return $o;
	}
	
	function means($fe, $av, $uv, $fu){
		$fe = data::agg($fe, $av, $uv, $fu);
		showneu(flip3($fe), "1,3", "150, 60");
	}

	//_________ voll funktionierendes Beispiel __________
	// $z = -1; for ($p = 0; $p < 2; ++$p) for ($i = 0; $i < 2; ++$i) {++$z; $fe["pat"][$z] = $p; $fe["index"][$z] = $i; for ($j = 0; $j < 2; ++$j) $fe["c".$j][$z] = mt_rand(1, 10);} show3($fe);
	// $fe = data::casestovars($fe, "pat", "index", "^c"); show3($fe);	
	function casestovars($fe, $uv, $index, $av){
		$fe = self::get($fe, "$uv|$index|$av");
		$uvf = self::vl($fe, $uv); $ugr = count($uvf);
		$avf = self::vl($fe, $av); $avz = count($avf); $av = fins($avf, "|", "^", "$");
		
		if ($ugr > 1) {$t = "tmp_uv"; $uv = $t; $fe = self::comp($fe, "@$t@ = ';'.@".implode("@.';'.@", $uvf)."@.';' ;"); } else $uv = $uvf[0];
		
		$st = self::uvstufen($fe, $index);
		
		for($j = 0; $j < count($st); ++$j) {
			$u = $st[$j];
			$o[$j] = self::filter($fe, "@$index@ == '$u'");
			$o[$j] = data::rename($o[$j], "/($av)/", "\$1_$u");
		}
		$o2 = self::merge($o, $uv);
		if ($ugr > 1) for ($j = 0; $j < $ugr; ++$j) $o2 = self::comp($o2, "@".$uvf[$j]."@ = explode(';', @$t@)[".($j + 1)."];");
		return data::get($o2, fins($uvf).",".fins($avf));
	}

	// $fe = data::rnd(10, 4, 1,  10);
	// $fe = data::rename($fe, "/^c([12])/", "aa\$1");
	// $fe = data::rename($fe, "/^c([34])/", "bb\$1"); show3($fe);
	// show3(data::varstocases($fe, array("^aa", "^bb"), "^lfn$", array("aa", "bb")));
	function varstocases($fe, $fe_vars, $idvars, $fe_neunames){
		for ($j = 0; $j < count($fe_vars); ++$j) $o[$j] = self::varstocases_base($fe, $fe_vars[$j], $idvars, $fe_neunames[$j]);
		return data::merge($o, "$idvars,recnum");
	}
	
	function varstocases_base($fe, $vars, $idvars, $neu = "neu"){
		$vf  = self::vl($fe, $vars  ); $v  = implode(",", $vf);
		$idf = self::vl($fe, $idvars); $id = implode(",", $idf); $id0 = $id;
		
		for ($j = 0; $j < count($vf); ++$j){
			$v = $vf[$j];
			$fe2 = self::get($fe, "^".$id0."$,^".$v);
			$fe2 = data::rename($fe2, "/$v/", $neu);
			$fe2 = self::comp($fe2, "@recnum@ = $j;");
			$fe2 = self::comp($fe2, "@var@ = $v;");
			$o[] = $fe2;
		}
		return add3all($o);
	}	
	
	// $fe = data::rnd(10, 2, 1, 3); $fe = data::sort($fe, "c1"); show3(data::index($fe, "c1"));
	function index($fe, $uv, $na = "recnum"){
		$uvf = self::vl($fe, $uv); $ugr = count($uvf);
		if ($ugr > 1) {$t = "tmp_uv"; $uv = $t; $fe = self::comp($fe, "@$t@ = ';'.@".implode("@.';'.@", $uvf)."@.';' ;"); } else $uv = $uvf[0];
		$fe = self::comp($fe, "@$na@ = 1;");
		for($i = 0; $i < count($fe[$uv]); ++$i) {
			if ($fe[$uv][$i] == $fe[$uv][$i - 1]) $fe[$na][$i] = $fe[$na][$i - 1] + 1;
		}
		if ($ugr > 1) $fe = self::get($fe, "^(?!$t)");
		return $fe;
	}
	
	// $fe1 = data::rnd(5, 12, 1, 10); $fe1 = data::comp($fe1, "@aa@ = 12;"); 
	// $fe2 = data::rnd(5, 12, 1, 10); $fe2 = data::comp($fe2, "@bb@ = 34;"); $fe2 = data::rename($fe2, "/^c(0[5-9])/", "bb\$1");
	// show3($fe1); show3($fe2); show3(data::add(array($fe1, $fe2)));
	function add($fearr){
		for ($j = 0; $j < count($fearr); ++$j) $fearr[$j] = self::arrayfull($fearr[$j]);
		for ($j = 0; $j < count($fearr); ++$j) if ($j == 0) $fe = $fearr[0]; else $fe = self::add_base($fe, $fearr[$j]);
		return $fe;
	}
	
	function add_base($fe1, $fe2){
		while (list($k1, $j) = each($fe1)) if (!array_key_exists($k1, $fe2)) $fe2 = self::comp($fe2, "@$k1@ = '';"); reset($fe1); // felder in fe2 ergänzen falls fehlend
		while (list($k2, $j) = each($fe2)) if (!array_key_exists($k2, $fe1)) $fe1 = self::comp($fe1, "@$k2@ = '';"); reset($fe2); // felder in fe1 ergänzen falls fehlend
		while (list($k, $w) = each($fe1)) $fe12[$k] = array_merge($fe1[$k], $fe2[$k]);
		return $fe12;
	}

	function arrayfull($fe){
		while (list($k, $w) = each($fe)) $c[] = count($fe[$k]); reset($fe); $mx = max2($c);
		while (list($k, $w) = each($fe)) for($i = 0; $i < $mx; ++$i) if (!isset($fe[$k][$i])) $fe[$k][$i] = "";
		return $fe;
	}
	
	// $fe = data::rnd(5, 3); show::fe($fe); show::fe(data::rowinsert($fe, 1));
	function rowinsert($fe, $r){
		while (list($k, $w) = each($fe)) array_splice($fe[$k], $r, 0, "");
		return self::lfn($fe);
	}
	
	// $fe = data::rnd(5, 5); $k = preg_replace("/^c/", "v", array_keys($fe)); $fe = insertrow($fe, $k, 0); $s->fe($fe);
	function insertrow($fe, $infe, $r = 0){
		$kf = array_keys($fe);
		for($j = 0; $j < count($kf); ++$j) array_splice($fe[$kf[$j]], $r, 0, $infe[$j]);
		return $fe;
	}
	
	// $fe = data::rnd(10, 3); $fe = data::lfn($fe); $fe = data::deleterow($fe, 0); show3($fe);
	function deleterow($fe, $r){
		$kf = array_keys($fe);
		for($j = 0; $j < count($kf); ++$j) {$c = $kf[$j]; unset($fe[$c][$r]); $fe[$c] = array_values($fe[$c]);}
		return $fe;
	}	
	
	function arr2fe($arr){
		$z = -1;
		foreach ($arr as $k => $v) {$o["key"][++$z] = $k; $o["value"][$z] = $v; $o["lfn"][$z] = $z;}
		return $o;
	}
	
	// md(data::kette($fe, "lfn"));
	function kette($fe, $v, $tx = ","){
		$fe2 = data::get($fe, "^$v$");
		for ($i = 0; $i < count($fe[$v]); $i++) $o[] = $fe[$v][$i];
		return fins($o, $tx);
	}
	
	function append_ag($fe, $av, $uv, $kw){
		$fe = data::sort($fe, $uv, SORT_ASC);
		$fe = data::index($fe, $uv);
		#he($fe, 50);
		
		$ag = data::agg($fe, $av, $uv, $kw);
		$ag = data::comp($ag, "@recnum@ = 1;");
		#he($fe, 50);
		
		$fe2 = data::merge(array($fe, $ag), $uv.",recnum$");
		return $fe2;
	}
	
	function complete($fe, $o){
		$kf = array_keys($fe);
		$q = "if (nu(@".implode("@) and nu(@", $kf)."@)) @$o@ = 1;";
		$fe = data::comp($fe, $q);
		return $fe;
	}
	
	function komb($fe, $a, $b, $tx = "."){
		return data::comp($fe, "if (trim(@$a@) !== '' and trim(@$b@) !== '') @".$a.$b."@ = @$a@.'$tx'.@$b@;");
	}

	// $fe = data::komb2($fe, "v1[4-7]", "diag");
	function komb2($fe, $v, $o){
		$fe = data::recode($fe, $v, "array('/^$/')", "array('9')");
		$k = data::vls($fe, $v);
		$k = preg_replace('/,/', '.', $k);
		$fe = data::comp($fe, "@$o@ = $k;");
		return($fe);
	}
	
	// $fe = data::copyvars($fe, "vib_[036]", "/vib_/", "vibk_");
	function copyvars($fe, $v, $preg_what, $preg_by){
		$v = data::vl($fe, $v);
		#md($v);
		#md(count($v));
		for($j = 0; $j < count($v); ++$j) {
			#md($j);
			$v0 = $v[$j];
			$v1 = preg_replace($preg_what, $preg_by, $v0);
			#md("@$v1@ = @$v0@");
			$fe = data::comp($fe, "@$v1@ = @$v0@;");
		}
			
		return $fe;
	}
}

class chart {
	public $save;
	
	function __construct() {
 		$this->color = "white";
		$this->neu = true;
		$this->vonunten = 1;
		$this->CI = 1;
		$this->plottab = 1;
	}

	// $fe = data::rnd(250, 2, 1, 99); $fe = data::comp($fe, "@gruppe@ = trunc2(@c01@ / 20);"); $c = new chart(); $c->save = $d."/out/box.png"; $c->box(1, $fe, "gruppe", "c02", "0, 100");
	function box($fe, $uv, $av, $rg = "0,100"){
		if ($this->name == "") $this->name = __FUNCTION__.".png";
		$ofi = getcwd()."/out/".$this->name;
		if ($this->neu == 1) {
			$l = chr(10);
			$fe = data::get($fe, $uv.",".$av);
			$uvf = data::vl($fe, $uv); $uv = $uvf[0]; $lb = label::codes($fe, $uv);
			$avf = data::vl($fe, $av); $av = $avf[0];
			$f = "/eigenes/downs/temp/box.dat"; export::asc($fe, $f);
			$r .= "options(warn=-1); library(ggplot2);".$l;
			$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x = subset(x, complete.cases(x));".$l;
			$r .= " x[, '$uv'] <- paste('gr', x[, '$uv'], sep = '');".$l;
			$r .= "gg <- ggplot(x, aes(y = $av, x = $uv)) +  
				stat_boxplot(geom ='errorbar', width = 0.1) + geom_boxplot(fill = 'lightblue', color = 'grey33')  +  
				stat_summary(fun.y = mean, geom = 'point', shape = 3, size = 4) + 
				#geom_jitter(position = position_jitter(width = .3), size = 1, color = 'grey77') + 
				scale_x_discrete(labels = c($lb)) + xlab('') + ylab('') +  
				theme(legend.position = 'none', axis.text.y = element_text(size = 12, colour = 'grey33'),axis.text.x = element_text(size = 14));
				ggsave(gg, file = '$ofi', dpi = 600); rm(gg); rm(x);".$l;
			$fi = "/eigenes/downs/temp/box.r"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
		}
		$fi = fromto($ofi, getcwd()."/", "");
		echo $l.$l."<img src='$fi' style = 'height:40%; width:40%;' >".$l.$l;
	}
	
	// $fe = data::rnd(15, 4); $c->name= "box2.png"; $c->box1group($fe, "c0[1-3]", "0, 125, 25");
	function box1group($fe, $av, $ylim = "0, 100, 10"){
		$p = getcwd(); $t = "/eigenes/downs/temp/";
		if ($this->name == "") $this->name = __FUNCTION__.".png";
		$ofi = $p."/out/".$this->name;
		if ($this->neu == 1) {
			$l = chr(10);
			$fe = data::get($fe, $av);
			$fe = data::comp($fe, "@alle@ = 1;");
			$avf = data::vl($fe, $av); $avz = count($avf);
			for ($j = 0; $j < $avz; ++$j) $avfl[$j] = label::v($avf[$j]); 
			$avl = fins($avfl, ",", "'", "'"); 
			$avs = label::set($avf[0]);
			
			$f = $t."box.dat"; 
			export::asc($fe, $f."0");
			
			$fe = data::varstocases($fe, array($av), "alle", array("av")); export::asc($fe, $f);
			
			$f = $t."box.dat"; export::asc($fe, $f);
			$r .= "options(warn=-1); library(ggplot2); library(ggtext); yl = c($ylim)[1:2];".$l;
			$r .= "x <- read.table('$f', header = TRUE); y0 <- read.table('".$f."0', header = TRUE);".$l;
			$r .= "gg <- ggplot(x, aes(y = av, x = var)) +  
				stat_boxplot(geom = 'errorbar', width = 0.1) + geom_boxplot(fill = 'green', color = 'grey33') +
				stat_summary(fun.y = mean, geom = 'errorbar', aes(ymax = ..y.., ymin = ..y..), width = .75, linetype = 'dashed') +
				scale_x_discrete(labels = c($avl)) + xlab('') + ylab('') +
				scale_y_continuous(limits = yl, breaks = seq($ylim), expand = c(0, 0)) +  
				ggtitle('$avs') + 
				theme(legend.position = 'none', 
					axis.text.x = element_text(size = 14, colour = 'black'), axis.ticks.x = element_blank(),
					axis.text.y = element_text(size = 14, colour = 'black'),
					axis.title = element_text(size = 14),
					axis.line  = element_line(color = 'grey55'),
					plot.title = element_text(size = 28, hjust = 0.5),
					aspect.ratio=0.75
				);";
				
			$z = -1;
			for ($i = 0; $i < $avz; ++$i){
				for ($j = 0; $j < $avz; ++$j){
					$fe3["a"][++$z] = $avf[$i]; 
					$fe3["b"][+$z] = $avf[$j];
					$fe3["c"][+$z] = $i + 1;
					$fe3["d"][+$z] = $j + 1;
				}
			}
			$fe3 = data::filter($fe3, "@c@ !== @d@ and @c@ < @d@");
			
			$px0 = 1; $px1 = 1; $ogr = fromto($ylim, ",", ",");
			for ($i = 0; $i < count($fe3["a"]); ++$i){
				
				$px0 = $fe3["c"][$i] + .1;
				$px1 = $fe3["d"][$i] - .1;
				$y = $ogr - 5 - $i * 5;
				
				$a1 =  $fe3["a"][$i];
				$a2 =  $fe3["b"][$i];
				
				$r .= "gg = gg + geom_segment(aes(x = $px0, y = $y, xend = $px1, yend = $y));";
				$r .= "gg = gg + geom_segment(aes(x = $px0, y = $y, xend = $px0, yend = $y - 1));";
				$r .= "gg = gg + geom_segment(aes(x = $px1, y = $y, xend = $px1, yend = $y - 1));";
				
				$r .= "w$i = wilcox.test(y0\$$a1, y0\$$a2, data = y0, paired = T, correct = F); p = w$i\$p.value; if (p < 0.001) {p = 0.001; k = ' < ';} else {k = ' = ';};";
				$r .= "gg = gg + annotate(geom = 'richtext', x = $px0 + 0.5, y = $y, label = pa2('p', k, round(p, 3)), label.color = NA, fill = 'grey92');";
			}
			$r .= "f = friedman.test(as.matrix(y0)); pf = f\$p.value; if (pf < 0.001) {pf = 0.001; k = ' < ';} else {k = ' = ';};
			       gg = gg + annotate(geom = 'richtext', y = $ogr - 5, x = $avz, label = pa2('Overall-Test: p', k, round(pf, 3)), label.color = NA, fill = 'grey92');";				
				
			$r .= "ggsave(gg, file = '$ofi', dpi = 600); rm(gg); rm(x); rm(y0);".$l;
			$fi = $t."temp/box.r"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
			
			$lg = $t."Rserve.log";
		}
		$fi = fromto($ofi, getcwd()."/", "");
		$h = 700; echo $l.$l."<table border = 0 id = toptab><tr><td height = $h width = $h><img src = '$fi' style = 'height: 95%; width: 95%;'/></td><tr></table>";
		echop("<br><textarea id = rcmd rows = 8 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $r)."</textarea> ");
		
		$te = read2($lg); 
		echop("<br><textarea id = log rows = 8 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $te)."</textarea> ");
		echo "<script language = javascript>ta = document.getElementById('log'); ta.scrollTop = ta.scrollHeight; </script>";
	}	
	
	

	// $fe = data::rnd(130, 3); $fe = data::comp($fe, "@gruppe@ = trunc2(@c01@/20) + 1;"); $c = new chart(); $c->save = $d."/out/gruppe.png"; $c->bar(1, $fe, "gruppe", "0, 50, 10");
	function bar($fe, $uv, $ylim = "0, 100, 10"){
		$db = currdb();
		$ofi = ofi($db, $uv."_bar_freq");
		if ($this->neu == 1) {
			$uv = implode(",", data::vl($fe, $uv));
			$l = chr(10);
			$t = "anz_tmp";
			$fe = data::comp($fe, "@$t@ = 1;");
			$o = data::agg($fe, $t, $uv, "sum2");
			$su = sum2($o[$t."_sum2"]);
			$o = data::comp($o, "@pr@ = format2((@".$t."_sum2@ / $su) * 100, '0.0');");
			$f = "/eigenes/downs/temp/".$this->name; export::asc($o, $f.".dat"); $cl = label::codes($fe, $uv);
			$r .= "library(ggplot2);".$l;
			$r .= "x <- read.table('$f.dat', header = TRUE); yl = c($ylim)[1:2];
				gg <- ggplot(x, aes(x = as.factor($uv), y = pr)) + 
				geom_bar(stat = 'identity', fill = 'grey88', color = 'grey44') + ylab('Prozent') + xlab('".label::v($uv)."') + 
				scale_x_discrete(labels = c($cl)) + 
				scale_y_continuous(limits = yl, breaks = seq($ylim)) +
				theme(axis.text.x = element_text(size = 11, colour = 'grey33'), axis.ticks = element_blank(), axis.text.y = element_text(size = 14));
				gg = gg + annotate('text', x = 1.5, y = 42, size = 6, label = paste('n =', sum(x\$anz_tmp_sum2)));
				ggsave(gg, file = '/eigenes/www/$db/out/".$this->name."', dpi = 600); rm(x); rm(gg); rm(x);".$l;
				write2($r, $f.".r"); #exec("sudo Rscript --vanilla '$f.r' > '$f.log' 2>&1 "); 
				$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
		}
		$fi = fromto($ofi, "/eigenes/www/$db/", "");
		echo($l."<img src = 'out/".$this->name."' style = 'height:450px; width:auto;' >");
		echop("r-cmd<br><textarea id = mytextarea rows = 3 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $r)."</textarea> ");
		$te = read2($f.".log"); echop("log<br><textarea id = mytextarea rows = 2 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $te)."</textarea> ");

	}

	function bar_mit_x_numerisch($neu, $fe, $uv, $ylim = "0, 100, 10", $xlim = ""){
		$db = currdb();
		$uv = implode(",", data::vl($fe, $uv));
		$ofi = ofi($db, $uv."_bar_freq");
		if ($neu == 1) {
			$l = chr(10);
			$t = "anz_tmp";
			$fe = data::comp($fe, "@$t@ = 1;");
			$o = data::agg($fe, $t, $uv, "sum2");
			$su = sum2($o[$t."_sum2"]);
			$o = data::comp($o, "@pr@ = round((@".$t."_sum2@ / $su) * 100, 1);");
			$f = "/tmp/tmp.dat"; export::asc($o, $f); $cl = label::codes($o, $uv);
			$r .= "options(warn=-1); library(ggplot2);".$l;
			if ($xlim == "") {$x = "scale_x_discrete(labels = c($cl))"; $u = "factor($uv)";} else {$x = "scale_x_continuous(limits = xl, breaks = seq($xlim), labels = seq($xlim))"; $u = $uv;}
			$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x)); xl = c($xlim)[1:2]; yl = c($ylim)[1:2];
				gg <- ggplot(x, aes(x = $u, y = pr)) + geom_bar(stat = 'identity', fill = 'grey88', colour = 'grey44') + scale_y_continuous(limits = yl, breaks = seq($ylim)) + $x + ylab('Prozent') + xlab('".label::v($uv)."') + 
					theme(axis.text.x = element_text(size = 11, colour = 'grey33'), axis.ticks = element_blank(), axis.text.y = element_text(size = 14));
				ggsave(gg, file = '$ofi.png', dpi = 600); rm(x); rm(gg);".$l;
				$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
		}
		$fi = fromto($this->save, getcwd()."/", "");
		echo $l.$l."<img src='$fi' style = 'height:40%; width:40%;'/>".$l.$l;
	}
	
	// $fe = data::rnd(150, 5); for($j = 1; $j <= 5; ++$j) $fe = data::comp($fe, "@c0$j@ < 50 ? @k0$j@ = 0 : @k0$j@ = 1;"); $c = new chart(); $c->save = $d."/out/odd.png"; $c->odd(1, $fe, "^k0[2-5]$", "^k01$", "0, 4, .5");
	function odd($neu, $fe, $many_av, $one_uv, $ylim = "0, 3, .25"){
		if ($neu == 1) {
			$l = chr(10);
			$uvf1 = data::vl($fe, $one_uv ); $uv1 = implode(",", $uvf1);
			$uvf2 = data::vl($fe, $many_av); $uv2 = implode(",", $uvf2);
			for($j = 0; $j < count($uvf2); ++$j) {
				$o[$j] = r_odds($fe, $uvf1[0], $uvf2[$j]);
				$o[$j] = data::comp($o[$j], "@var@ = '".$uvf2[$j]."';");
			}
			$o2 = data::add($o);
			$o2 = data::comp($o2, "@l@ = givelabel(@var@);");
			$o2 = data::fu($o2, "^odds|^p|lo|up", "format2(@, '0.0000')");
			$f = "/tmp/tmp.dat"; export::asc($o2, $f);
			
			$r .= "x <- read.table('$f', header = TRUE);".$l;
			$r .= "x['er_l'] <- x\$odds - x\$lower;".$l;
			$r .= "x['er_u'] <- x\$upper - x\$odds;".$l;
			$r .= "x[, 'l'] <- paste(x[, 'l'], '\rp = ', x[, 'p']);".$l;
			$r .= "options(warn=-1); library(ggplot2);".$l;
			$r .= "gg <- ggplot(x, aes(x = x\$var, y = x\$odds - 1)) + 
				geom_bar(stat = 'identity', fill = 'blue', colour = 'grey33') + geom_errorbar(aes(ymin = x\$odds - 1 - x\$er_l, ymax = x\$odds - 1 + x\$er_u), width = 0.25) + 
				scale_y_continuous(limits = c(c($ylim)[1:2]) - 1, breaks = seq($ylim) - 1, labels = seq($ylim)) + scale_x_discrete(limits = rev(x\$var), labels = rev(x\$l)) +
				geom_hline(yintercept = 0, colour = 'grey20', size = .45) + xlab('') + ylab('') + 
				theme(axis.ticks = element_blank(),axis.text.y = element_text(size = 12, colour = 'grey33'),axis.text.x = element_text(size = 14));".$l;
			$r .= "gg <- gg + coord_flip();".$l;
			$r .= "ggsave(gg, file = '".$this->save."', dpi = 600); ".$l;
			$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
		}
		$fi = fromto($this->save, getcwd()."/", "");
		echo $l.$l."<img src='$fi' style = 'height:40%; width:40%;' >";
	}
	
	// $fe = data::rnd(120, 3); $fe = data::comp($fe, "@c01@ < 50 ? @gr1@ = 0 : @gr1@ = 1;"); $fe = data::comp($fe, "@c02@ < 50 ? @gr2@ = 0 : @gr2@ = 1;"); 
	// $c = new chart(); $c->save = $d."/out/mean.png"; $c->mean(1, $fe, "^c03$", "^gr1$", "^gr2$", "30, 70, 10");
	function mean($neu, $fe, $av, $uv1, $uv2, $ylim = "0, 100, 10"){  // 2 lines with means and errorbars, agegr x Gender and BMI-Means,  www.cookbook-r.com/Graphs/Plotting_means_and_error_bars_%28ggplot2%29
		if ($neu == 1) {
			$l = chr(10);
			$uvf1 = data::vl($fe, $uv1); $uv1 = $uvf1[0]; $cl1 = label::codes($fe, $uv1); $c1 = data::uvstufen($fe, $uv1);
			$uvf2 = data::vl($fe, $uv2); $uv2 = $uvf2[0]; $cl2 = label::codes($fe, $uv2); $c2 = data::uvstufen($fe, $uv2);
			$avf  = data::vl($fe,  $av); $av  =  $avf[0]; $la = label::v($av);

			$fe = data::agg($fe, $av, $uv1.",".$uv2, "mean2,se2,count2");
			$fe = data::fu($fe, "^".$av, "format2(@, '0.00')");
			$f = "/eigenes/downs/temp/tmp.dat"; export::asc($fe, $f);
			
			$mean = $av."_mean2"; $sd = $av."_se2";
			$r .= "y <- read.table('$f', header = TRUE); y[] <- lapply(y, as.numeric);".$l;
			$r .= "options(warn=-1); library(ggplot2); palette <- c('red', 'blue', 'green', 'yellow', 'black', 'grey30', 'grey50', 'grey70', 'grey90');".$l;
			$r .= "pd <- position_dodge(.05); yl = c($ylim)[1:2];".$l;
			
			$r .= "gg <- ggplot(y, aes(x = $uv1, y = $mean, colour = factor(gr2))) + geom_line() + 
				geom_errorbar(aes(ymin = $mean - $sd, ymax = $mean + $sd), width = .1, position = pd) + 
				geom_point(position = pd, size = 5, shape = 21, fill = 'white') + xlab('') + ylab('".label::v($av)."') + 
				
				scale_y_continuous(limits = yl, breaks = seq($ylim)) + 
				scale_x_continuous(breaks = c(".fins($c1)."), labels = c($cl1)) + 
				scale_colour_manual(values = palette, labels = c($cl2)) + 
				
				theme(legend.justification = c(1,0), legend.position = c(.4,.8), legend.title = element_blank(), legend.text = element_text(size = 14), 
					axis.text.x = element_text(size = 14, colour = 'black'), axis.text.y = element_text(size = 14, colour = 'black'));".$l;
			
			$r .= "ggsave(gg, file = '".$this->save."', dpi = 600); ".$l;
			$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); #$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
			#exec("sudo Rscript '$fi'");
			exec("sudo Rscript --no-init-file --no-save --no-restore --verbose '$fi' > /eigenes/www/$db/out/km2.log 2>&1 &"); 
		}
		
		#$fi = fromto($this->save, getcwd()."/", "");
		#md($fi);
			
		#$f = getcwd()."/out/".$this->name;
		#$fi = fromto($f, getcwd()."/", "");
			
		#$h = 700; echo $l.$l."<table border = 0 id = toptab><tr><td height = $h width = $h><img src = '$fi' style = 'height: 95%; width: 95%;'/></td><tr></table>";
		#echo "<script language = javascript>document.getElementById('toptab').style.boxShadow = 'none';</script>";
			
		echop("r-cmd<br><textarea id = mytextarea rows = 30 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $r)."</textarea> ");
		#$te = read2("/eigenes/www/$db/out/km2.log"); echop("log<br><textarea id = mytextarea rows = 2 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $te)."</textarea> ");
		
	}
	
	// $fe = data::rnd(100, 5); $fe = data::comp($fe, "if (@c01@ < 50) @gr1@ = 0; else @gr1@ = 1;"); $fe = data::comp($fe, "if (@c02@ < 50) @gr2@ = 0; else @gr2@ = 1;");

	function bars_1uv_1av($fe, $av, $uv, $ylim = "0,100"){
		$l = chr(10); $av0 = $av;
		$fe = data::lfn($fe);
		$fe = data::get($fe, "lfn,".$uv.",".$av);
		$uvf = data::vl($fe, $uv); $uv = $uvf[0]; $cl = label::codes($fe, $uv);
		$avf = data::vl($fe, $av); $av = fins($avf); $avl = label::liste($avf); #md(fins($avl));
		$av2  = "'".implode("','", $avf)."'";
		$avl2 = "'".implode("','", $avl)."'";
		
		if ($this->neu == 1) {
			$fe = data::filter($fe, "trim(@$uv@) !== ''");
			$fe = data::comp($fe, "@$uv@ = 'gr'.@$uv@;");
			
			$f = getcwd()."/out/".$this->name; export::asc($fe, $f.".dat");
			
			$r .= "y = read.table('$f.dat', header = TRUE); y[] <- lapply(y, as.numeric); y <- subset(y, complete.cases(y));".$l;
			$r .= "y3mean = aggregate(y[, -c(1,2)], list(y\$$uv), mean2);".$l;
			$r .= "y3sd   = aggregate(y[, -c(1,2)], list(y\$$uv), se2)".$l;
			$r .= "colnames(y3mean) = c('$uv', 'mean'); colnames(y3sd) <- c('$uv', 'sd');".$l;
			$r .= "y4 = merge(y3mean, y3sd, by = c('$uv'));".$l;
			$r .= "y4\$$uv = factor(y4\$$uv);".$l;
			$r .= "library(ggplot2);".$l;
			$r .= "gg = ggplot(y4, aes(x = $uv, y = mean)) + 
					geom_bar(position = position_dodge(), stat = 'identity', color = 'gray', fill = 'white') + 
					geom_errorbar(aes(ymin = mean - sd, ymax = mean + sd), width = .15, position = position_dodge(.6))+ 
					theme(legend.justification = c(1,0), legend.position = c(1, 0.8),legend.title = element_blank(), legend.text = element_text(size = 18), axis.text.x = element_text(size = 18, colour = 'black'), 
						axis.text.y = element_text(size = 18, colour = 'black'), axis.ticks.x = element_blank(), axis.title=element_text(size = 18) ) +
					ylab('".label::v($av)." Mittel   \u00B1   SE') + xlab('') +
					scale_y_continuous(limits = c($ylim)) + 
					scale_x_discrete(labels = c($cl));".$l;
			
			$r .= "w = wilcox.test(".$av." ~ $uv, data = y, conf.int = TRUE);".$l;
			$r .= "gg = gg + annotate('text', x = 1.5, y = 42, size = 6, label = paste('p = ', round(w\$p.value, 3), sep = ''));".$l;
			$r .= "gg = gg + geom_segment(aes(x = 1, xend = 2, y = 40, yend = 40));".$l;
			$r .= "gg = gg + geom_segment(aes(x = 1, xend = 1, y = 40, yend = 39));".$l;
			$r .= "gg = gg + geom_segment(aes(x = 2, xend = 2, y = 40, yend = 39));".$l;
			
			$r .= "ggsave(gg, file = '$f', dpi = 600);".$l;
			write2($r, $f.".r");
			exec("sudo Rscript '$f.r' > '$f.log' 2>&1 ");
			
		}
		echo($l."<img src = 'out/".fromto($f, "/out/", "")."' style = 'height:40%; width:40%;' >");
		echop("r-cmd<br><textarea id = mytextarea rows = 15 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $r)."</textarea> ");
		$te = read2($f.".log"); echop("log<br><textarea id = mytextarea rows = 8 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $te)."</textarea> ");
		
	}
	
	// $fe = data::rnd(100, 5); $fe = data::comp($fe, "if (@c01@ < 50) @gr1@ = 0; else @gr1@ = 1;"); $fe = data::comp($fe, "if (@c02@ < 50) @gr2@ = 0; else @gr2@ = 1;");
	// $ch->bars_2uv_1av($fe, "gr1", "gr2", "^c0[4]$", "0,80");
	function bars_2uv_1av($fe, $uv1, $uv2, $av, $yrg = "0,100"){ //r x c Design und 1 av als Balken-mean, z.B. 3 Altersgruppen x Geschlecht und BMI als av = 6 Säulen
		$fe = data::get($fe, $uv1.",".$uv2.",".$av);
		$l = chr(10);
		$db = currdb();
		$ofi = ofi($db, $uv1."_bars_2uv_1av_".$uv1."_".$uv2."_".$av).".png";

		if ($this->neu == 1) {
			$av = data::vl($fe, $av); $lu = label::v($uv1); $cl = label::codes($fe, $uv1);
			$f = "/tmp/tmp.dat"; export::asc($fe, $f);
			$r .= "y <- read.table('$f', header = TRUE); y[] <- lapply(y, as.numeric); y <- subset(y, complete.cases(y));".$l;
			$r .= "y3mean <- aggregate(y[, -c(1,2)], list(y\$$uv1, y\$$uv2), mean2);".$l;
			$r .= "y3sd   <- aggregate(y[, -c(1,2)], list(y\$$uv1, y\$$uv2), se2)".$l;
			$r .= "colnames(y3mean) <- c('$uv1', '$uv2', 'mean'); colnames(y3sd) <- c('$uv1', '$uv2', 'sd');".$l;
			$r .= "y4 <- merge(y3mean, y3sd, by = c('$uv1', '$uv2'));".$l;
			$r .= "y4\$$uv1 <- factor(y4\$$uv1);".$l;
			$r .= "y4\$$uv2 <- factor(y4\$$uv2);".$l;
			$r .= "library(ggplot2);".$l;
			$r .= "gg <- ggplot(y4, aes(x = $uv1, y = mean, fill = $uv2)) + 
					geom_bar(position=position_dodge(), stat = 'identity') + xlab('') + ylab('') +
					geom_errorbar(aes(ymin = mean - sd, ymax = mean + sd), width=.2, position=position_dodge(.9))+ 
					theme(legend.justification = c(1,0), legend.position = c(1, 0.8),legend.title = element_blank(), legend.text = element_text(size = 14), axis.text.x = element_text(size = 14, colour = 'black'), 
						axis.text.y = element_text(size = 14, colour = 'black'), axis.ticks.x = element_blank()) +
					scale_y_continuous(limits = c($yrg)) + 
					scale_x_discrete(labels = c($cl));".$l;
			$r .= "ggsave(gg, file = '$ofi', dpi = 600); rm(list=ls());".$l;
			$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
		}
		$fi = fromto($ofi, "/eigenes/www/$db/", "");
		echo($l."<img src = '$fi' style = 'height:40%; width:40%;' >");
	}
	

	# $fe = data::rnd(100, 4); $fe = data::comp($fe, "@c01@ < 50 ? @gr@ = 0 : @gr@ = 1;");
	# $c = new chart(); $c->name = "aaa".str::zufall(3).".png"; $c->meanrep($fe, "^c0[2-4]$", "^gr$", "0, 100, 10");
	function meanrep($fe, $av, $uv, $ylim, $leg = ".9,.4"){  // 2 UV (z.B. 2 Gruppen x pre-posttest)  www.cookbook-r.com/Graphs/Plotting_means_and_error_bars_%28ggplot2%29/
		$l = chr(10); $av0 = $av;
		$fe = data::lfn($fe);
		$fe = data::get($fe, "lfn,".$uv.",".$av);
		$uvf = data::vl($fe, $uv); $uv = $uvf[0]; $cl = label::codes($fe, $uv); 
		
		$avf = data::vl($fe, $av); $av = fins($avf); $av2  = "'".implode("','", $avf)."'";
		$avl = label::liste($avf); $avl2 = "'".implode("','", $avl)."'";
		
		if ($this->neu) {
			$f = getcwd()."/out/".$this->name;
			$fe = data::filter($fe, "trim(@$uv@) !== ''"); $fe0 = $fe; export::asc($fe0, $f.".dat0", 1);
			
			$fe = data::comp($fe, "@$uv@ = 'gr'.@$uv@;");
			$fe = data::recode($fe, $av, "array('/^$/')", "array('NA')");
			
			$fe = data::varstocases($fe, array($av), "^lfn$,$uv", array("av"));
			$fe = data::agg($fe, "av", "$uv|var", "mean2,se2,count2");
			
			$fe = data::rename($fe, array("/av_/"), array(""));
			$fe = data::rename($fe, array("/var/"), array("messwd"));
			$fe = data::fu($fe, "mean2|se2", "format2(@, '0.00')");
			export::asc($fe, $f.".dat");
			
			$r .= "source('/mnt/69/eigenes/commandr/functions.r');
				options(warn = -1); library(ggplot2); yl = c($ylim)[1:2];
				y0 <- read.table('$f.dat0', header = T, fill = T); x[] <- lapply(x, as.numeric);
				y <- read.table('$f.dat', header = T, fill = T);
				
				#write.table(y, file = '$f.yyy', sep = '\\t', quote = F, row.names = F);
				#write.table(y0, file = '$f.xxx', sep = '\\t', quote = F, row.names = F);
				
				y\$ymin = y\$mean2 - y\$se2; #y\$ymin = ifelse(y\$ymin < yl[1], yl[1], y\$ymin);
				y\$ymax = y\$mean2 + y\$se2; #y\$ymax = ifelse(y\$ymax > yl[2], yl[2], y\$ymax);
				pd <- position_dodge(.15); palette <- c('red', 'blue', 'green', 'yellow', 'black', 'grey30', 'grey50', 'grey70', 'grey90');
				
				gg <- ggplot(y, aes(x = messwd, y = mean2, group = $uv, colour = $uv, ymax = max(mean2) * 1.1)) + 
					ggtitle('".label::set($avf[0])."') + 
					geom_line(position = pd, size = 2) + geom_errorbar(aes(ymin = ymin, ymax = ymax), width = .2, position = pd, size = 1) +
					geom_point(position = pd, size = 7, shape = 21, fill = 'white') +  xlab('') + ylab('Mittelwert   \u00B1   SE') + 
					scale_x_discrete(breaks = c($av2), labels = c($avl2)) + 
					scale_y_continuous(limits = yl, breaks = seq($ylim), minor_breaks = seq($ylim)) + 
					scale_colour_manual(values = palette, labels = c($cl)) +
					theme(plot.title = element_text(size = 24, hjust = 0.5, face = 'bold'), 
						legend.justification = c(0,0), legend.position = c($leg), legend.title = element_blank(), legend.text = element_text(size = 12),
						axis.text.x = element_text(size = 14, colour = 'black'), axis.text.y = element_text(size = 18, colour = 'black'), axis.title=element_text(size = 18));".$l;
				
			for ($i = 0; $i < count($avf); ++$i){
				$a = $avf[$i];
				$r .= "n = nrow(y0);
					#n1 = nrow(y0[y0\$$uv == 1 & !is.na(y0\$$a), ]);
					#n2 = nrow(y0[y0\$$uv == 2 & !is.na(y0\$$a), ]);
					
					nm = nrow(y[is.na(y0\$$a), ]);
					ng = length(unique(y0[!is.na(y0\$$a), '$uv']));
					if (!n == nm & ng >= 2) {
						w$i <- kruskal.test($a ~ $uv, data = y0);
						gg <- gg + annotate('text', x = ".($i + 1).", y = c($ylim)[2]*.95, label = sign2(w$i\$p.value));
					}
					".$l;
			}
			
			for ($i = 0; $i < count($avf) - 1; ++$i){
				$r .= "ng = length(unique(y[!is.na(y\$$uv), '$uv']));
					if (ng >= 2) {
						wd$i <- kruskal.test(".$avf[$i]." - ".$avf[$i + 1]." ~ $uv, data = y0);
						gg <- gg + annotate('text', x = ".($i + 1 + .5).", y = c($ylim)[2] * .90, label = sign2(wd$i\$p.value));
					}
					".$l;
			}
				
			$r .= "ggsave(gg, file = '$f', dpi = 150); rm(gg); rm(y);".$l;
			$fi = $f.".r"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
			
			$lg = "/eigenes/downs/temp/Rserve.log"; #exec("truncate -s 0 $lg"); 
		}
		$fi = fromto($f, getcwd()."/", "");
		$h = 700; echo $l.$l."<table border = 0 id = toptab><tr><td height = $h width = $h><img src = '$fi' style = 'height: 95%; width: 95%;'/></td><tr></table>";
		echop("<br><textarea id = rcmd rows = 15 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $r)."</textarea> ");
		
		$te = read2($lg); 
		echop("<br><textarea id = log rows = 8 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $te)."</textarea> ");
		echo "<script language = javascript>ta = document.getElementById('log'); ta.scrollTop = ta.scrollHeight; </script>";
	}
	
	// $fe = data::rnd(25, 5); $fe = data::comp($fe, "if (@c01@ < 30) @gr@ = 0; else @gr@ = 1;"); he($fe); $c->meanrepbars($fe, "^c0[2345]$", "gr", "0, 120,10");
	function meanrepbars($fe, $av, $uv, $ylim){  // 2 UV (z.B. 2 Gruppen x pre-posttest mit bars)  www.cookbook-r.com/Graphs/Plotting_means_and_error_bars_%28ggplot2%29/
		$l = chr(10);
		$db = currdb();
		
		if ($this->name == "") $this->name = __FUNCTION__.".png";
		$f = getcwd()."/out/".$this->name;
		
		$fe = data::get($fe, "lfn,".$uv.",".$av);
		$avf = data::vl($fe, $av); $av = fins($avf); $avl = label::liste($avf); $avl2 = fins($avl,",", "'", "'");
		$uvf = data::vl($fe, $uv); $uv = fins($uvf);
		
		md($avl2);
		
		if ($this->neu == 1) {
			$t = "av";
			$fe2 = data::varstocases($fe, array($av), "^lfn$,$uv", array($t));
			$avf = data::vl($fe, $av);
			$o = data::agg($fe2, $t, $uv."|recnum", "mean2,se2");
			$o = data::fu($o, "mean|se", "format2(@, '0.00')");
			#show3($o); return;
			
			$o = data::comp($o, "@$uv@ = label::c('$uv', @$uv@);");
			$o = data::comp($o, "\$a = explode(',', '$av'); @recnum@ = label::v(\$a[@recnum@]);");
			
			$d = "/eigenes/downs/temp/meanrep.dat"; export::asc($o, $d);
			$r .= "y <- read.table('$d', header = TRUE);".$l;
			$r .= "options(warn=-1); library(ggplot2); yl = c($ylim)[1:2];".$l;
			$r .= "pd <- position_dodge(.1); palette <- c('grey80', 'grey30', 'grey50', 'grey70', 'grey90')".$l;
			$r .= "gg <- ggplot(y, aes(x = factor(recnum, levels = c($avl2)), y = av_mean2, group = $uv, fill = $uv)) + 
				geom_bar(position = position_dodge(), stat = 'identity', colour = 'grey33') +  ylab('') + xlab('') + 
				geom_errorbar(aes(ymin = av_mean2 - av_se2, ymax = av_mean2 + av_se2),size =.3, width =.1, position = position_dodge(.9)) + 
				
				scale_y_continuous(limits = yl, breaks = seq($ylim)) +  
				#scale_x_discrete(labels = c($avl2), values = palette) + 
				
				theme(legend.justification = c(1,0), legend.position = c(.65, .85), legend.title = element_blank(), legend.text = element_text(size = 14), 
					axis.text.x = element_text(size = 14, colour = 'black'), 
					axis.text.y = element_text(size = 14, colour = 'black')
				);
			      ggsave(gg, file = '$f', dpi = 150); rm(x);".$l;
			write2($r, $d.".r"); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
			
			$lg = "/eigenes/downs/temp/Rserve.log"; #exec("truncate -s 0 $lg"); 
		}
		
		$fi = fromto($f, getcwd()."/", "");
		$h = 700; echo $l.$l."<table border = 0 id = toptab><tr><td height = $h width = $h><img src = '$fi' style = 'height: 95%; width: 95%;'/></td><tr></table>";
		echop("<br><textarea id = rcmd rows = 15 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $r)."</textarea> ");
		
		$te = read2($lg); 
		echop("<br><textarea id = log rows = 8 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $te)."</textarea> ");
		echo "<script language = javascript>ta = document.getElementById('log'); ta.scrollTop = ta.scrollHeight; </script>";
	}
	
	/*
	$fe = data::rnd(25, 5); $fe = data::comp($fe, "if (@c01@ < 30) @gr@ = 0; else @gr@ = 1;"); he($fe);
	$c->meanrepbars($fe, "^c0[2345]$", "gr", "0, 120,10");
	//*/	
	function meanrepbars1group($fe, $av, $ylim = "0,100,10"){  // t0,t1,t2
		$l = chr(10);
		$db = currdb();
		
		if ($this->name == "") $this->name = __FUNCTION__.".png";
		$f = getcwd()."/out/".$this->name;
		$d = "/eigenes/downs/temp/meanrep.dat"; 
		
		$fe = data::get($fe, "lfn,".$av); export::asc($fe, $d."0");
		$uv = "alle"; $fe = data::comp($fe, "@alle@ = 1;");
		$avf = data::vl($fe, $av); $avz = count($avf); $av = fins($avf); $avl = label::liste($avf); $avl2 = fins($avl,",", "'", "'");
		$uvf = data::vl($fe, $uv); $uv = fins($uvf);
		$avs = label::set($avf[0]);
		
		if ($this->neu == 1) {
			$t = "av";
			$fe2 = data::varstocases($fe, array($av), "^lfn$,$uv", array($t));
			#he($fe2); #return;
			$avf = data::vl($fe, $av);
			$o = data::agg($fe2, $t, "var,alle", "mean2,se2");
			$o = data::fu($o, "mean|se", "format2(@, '0.00')");
			$o = data::comp($o, "@var@ = label::v(@var@);");
			
			export::asc($o, $d);
			$r .= "y = read.table('$d', header = TRUE); y0 <- read.table('".$d."0', header = T); y0[] <- lapply(y0, as.numeric);".$l;
			$r .= "options(warn=-1); library(ggplot2); library(ggtext); yl = c($ylim)[1:2];".$l;
			$r .= "pd = position_dodge(.1); palette <- c('grey44', 'grey30', 'grey50', 'grey70', 'grey90')".$l;
			
			$r .= "gg = ggplot(y, aes(x = factor(var, levels = c($avl2)), y = av_mean2, group = $uv)) + 
				geom_bar(position = position_dodge(), stat = 'identity', colour = 'grey50', fill = 'grey80') +  ylab('Mittelwert  \u00B1   SE\n') + xlab('') + 
				geom_errorbar(aes(ymin = av_mean2 - av_se2, ymax = av_mean2 + av_se2),size =.4, width =.05, position = position_dodge(.9)) + 
				
				scale_y_continuous(limits = yl, breaks = seq($ylim), expand = c(0, 0)) +  
				ggtitle('$avs') + 
				
				theme(legend.position = 'none', 
					axis.text.x = element_text(size = 14, colour = 'black'), axis.ticks.x = element_blank(),
					axis.text.y = element_text(size = 14, colour = 'black'),
					axis.title = element_text(size = 14),
					axis.line  = element_line(color = 'grey55'),
					plot.title = element_text(size = 28, hjust = 0.5),
					aspect.ratio=0.75
				);";
			
			$z = -1;
			for ($i = 0; $i < $avz; ++$i){
				for ($j = 0; $j < $avz; ++$j){
					$fe3["a"][++$z] = $avf[$i]; 
					$fe3["b"][+$z] = $avf[$j];
					$fe3["c"][+$z] = $i + 1;
					$fe3["d"][+$z] = $j + 1;
				}
			}
			$fe3 = data::filter($fe3, "@c@ !== @d@ and @c@ < @d@");
			
			$px0 = 1; $px1 = 1; $ogr = fromto($ylim, ",", ",");
			for ($i = 0; $i < count($fe3["a"]); ++$i){
				
				$px0 = $fe3["c"][$i];
				$px1 = $fe3["d"][$i];
				$y = $ogr - 5 - $i * 4;
				
				$a1 =  $fe3["a"][$i];
				$a2 =  $fe3["b"][$i];
				
				$r .= "gg = gg + geom_segment(aes(x = $px0, y = $y, xend = $px1, yend = $y));";
				$r .= "gg = gg + geom_segment(aes(x = $px0, y = $y, xend = $px0, yend = $y - 1));";
				$r .= "gg = gg + geom_segment(aes(x = $px1, y = $y, xend = $px1, yend = $y - 1));";
				
				$r .= "w$i = wilcox.test(y0\$$a1, y0\$$a2, data = y0, paired = T, correct = F); p = w$i\$p.value; if (p < 0.001) {p = 0.001; k = ' < ';} else {k = ' = ';};";
				$r .= "gg = gg + annotate(geom = 'richtext', x = $px0 + 0.5, y = $y, label = pa2('p', k, round(p, 3)), label.color = NA, fill = 'grey92');";
			}
			
			$r .= "f = friedman.test(as.matrix(y0)); pf = f\$p.value; if (pf < 0.001) {pf = 0.001; k = ' < ';} else {k = ' = ';};
			       gg = gg + annotate(geom = 'richtext', y = $ogr - 5, x = $avz, label = pa2('Overall-Test: p', k, round(pf, 3)), label.color = NA, fill = 'grey92');";
				
			$r .= "ggsave(gg, file = '$f', dpi = 300); rm(x);".$l;
			write2($r, $d.".r"); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
			
			$lg = "/eigenes/downs/temp/Rserve.log"; #exec("truncate -s 0 $lg"); 
		}		
		$fi = fromto($f, getcwd()."/", "");
		$h = 700; echo $l.$l."<table border = 0 id = toptab><tr><td height = $h width = $h><img src = '$fi' style = 'height: 95%; width: 95%;'/></td><tr></table>";
		#echop("<br><textarea id = rcmd rows = 15 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $r)."</textarea> ");
		
		#$te = read2($lg); 
		#echop("<br><textarea id = log rows = 8 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $te)."</textarea> ");
		#echo "<script language = javascript>ta = document.getElementById('log'); ta.scrollTop = ta.scrollHeight; </script>";
	}

	
	/*
	$ x <- data.frame(c(1,1,1,2,2,2),c(1,1,2,2,3,3),c(21,32,42,13,33,14)); colnames(x) <- c('gr','av', 'wert');
	library(ggplot2);
	pd <- position_dodge(.1); 
	ggplot(x, aes(x = av, y = wert, group = gr, colour = gr)) + geom_line(position = pd) + geom_point(aes(shape = gr), fill = "white", position = pd, size = 8) + scale_shape_manual(values = c(21, 22))
	//*/
	
	function binrep($fe, $av, $uv, $ylim, $lpos){
		$l = chr(10);
		$fe = data::lfn($fe);
		$fe = data::get($fe, "lfn,".$uv.",".$av);
		#he($fe); return;
		$uvf = data::vl($fe, $uv); $uv = $uvf[0]; $cl = label::codes($fe, $uv);
		$avf = data::vl($fe, $av); $av = fins($avf); $avl = label::liste($avf);
		$av2  = "'".implode("','", $avf)."'";
		$avl2 = "'".implode("','", $avl)."'";
		
		if ($this->neu == 1) {
		
		
			$f = getcwd()."/out/".$this->name;
			#$fe = data::filter($fe, "trim(@$uv@) !== ''");
			$fe = data::comp($fe, "@$uv@ = 'gr'.@$uv@;");
			
			export::asc($fe, $f.".dat");
			$r .= "source('/mnt/69/eigenes/commandr/functions.r');".$l;
			$r .= "y <- read.table('$f.dat', header = T, fill = T);".$l;
			$r .= "library(reshape); y2 <- melt(y, id.vars = c('$uv', 'lfn'), measure.vars = c($av2), variable.name = 'value');".$l;
			$r .= "y3mean <- aggregate(y2[, -c(1,2,3)], list(y2\$$uv, y2\$variable), pm2);".$l;
			$r .= "y3cil  <- aggregate(y2[, -c(1,2,3)], list(y2\$$uv, y2\$variable), pcil);".$l;
			$r .= "y3ciu  <- aggregate(y2[, -c(1,2,3)], list(y2\$$uv, y2\$variable), pciu);".$l;
			
			$r .= "colnames(y3mean) <- c('gruppe', 'messwd', 'mean');".$l;
			$r .= "colnames(y3cil ) <- c('gruppe', 'messwd', 'cil' );".$l;
			$r .= "colnames(y3ciu ) <- c('gruppe', 'messwd', 'ciu' );".$l;
			$r .= "y4 <- merge(y3mean, y3cil, by = c('gruppe', 'messwd'));".$l;
			$r .= "y4 <- merge(y4, y3ciu, by = c('gruppe', 'messwd')); y4 = runde_df(y4, 1);".$l;
			
			$r .= "write.table(y4, file = '$f.y', sep = '\\t', quote = F, row.names = FALSE);".$l;
			
			$r .= "options(warn=-1); library(ggplot2); yl = c($ylim)[1:2];".$l;
			$r .= "pd <- position_dodge(.1); palette <- c('red', 'blue', 'green', 'yellow', 'black', 'grey30', 'grey50', 'grey70', 'grey90'); sh = c(21,22,23,24,25,26,27);".$l;
			
			$r .= "gg = ggplot(y4, aes(x = messwd, y = mean, group = gruppe, colour = gruppe, ymax = max(mean) * 1.2)) + 
					geom_line(position = pd, size = 1) + 
					geom_errorbar(aes(ymax = mean + (ciu - mean), ymin = mean - (mean - cil)), width = .2, position = pd,size = 1) +
					geom_point(position = pd, size = 10, shape = 21, fill = 'white') +  xlab('') + ylab('%   \u00B1   95%-KI') + 
					scale_x_discrete(breaks = c($av2), labels = c($avl2)) + scale_y_continuous(limits = yl, breaks = seq($ylim)) + scale_colour_manual(values = palette, labels = c($cl)) +
					theme(
						legend.justification = c(1,0), legend.position = c(.7, 0.1), legend.title = element_blank(), legend.text = element_text(size = 14), 
						axis.text.x = element_text(size = 14, colour = 'black'), axis.text.y = element_text(size = 14, colour = 'black'), axis.title=element_text(size = 18)
					);
					".$l;
			$r .= "ggsave(gg, file = '$f', dpi = 150); rm(gg); rm(y);".$l;
			$fi = $f.".r"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
			$lg = "/eigenes/downs/temp/Rserve.log"; #exec("truncate -s 0 $lg"); 
		}
		
		$fi = fromto($f, getcwd()."/", "");
		$h = 700; echo $l.$l."<table border = 0 id = toptab><tr><td height = $h width = $h><img src = '$fi' style = 'height: 95%; width: 95%;'/></td><tr></table>";
		echop("<br><textarea id = rcmd rows = 15 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $r)."</textarea> ");
		
		$te = read2($lg); 
		echop("<br><textarea id = log rows = 8 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $te)."</textarea> ");
		echo "<script language = javascript>ta = document.getElementById('log'); ta.scrollTop = ta.scrollHeight; </script>";
	}
	
	// $n = 300; $m = 3;
	// $fe = data::rnd($n, $m, 1, 100); $fe = data::lfn($fe);
	// $fe = data::comp($fe, "@tod@ = trunc2(@c01@ / 51);");
	// $fe = data::comp($fe, "@zeit@ = @c02@;");
	// $fe = data::comp($fe, "@gruppe@ = trunc2(@c03@ / 35);");
	// show3($fe);
	// # 							          xlim          ylim         ppos    tpos      lpos
	// $v = "gruppe" ;$ch->km4($fe, "zeit", "tod", $v, "0, 100, 10", "0, 1, 0.1", "5, .85", "10, .5", ".2, .3");
	
	function km4($fe, $zeit, $event, $gruppe, $xlim = "0, 60, 10", $ylim = "0, 1, 0.1", $ppos = "3, .1", $tpos = "1, .5", $lpos = ".1, .2"){
		$l = chr(10);
		$fe = data::lfn($fe);
		$st = uvstufen($fe, $gruppe);
		$db = currdb();
		$p = "/eigenes/downs/temp/";
		$ofi = ofi(currdb(), $this->name == "" ? "km_".str::zufall(5) : $this->name);
		
		if ($this->neu) {
			$fe = data::get($fe, $zeit."$,^".$event."$,^".$gruppe."$");
			$fe = data::filter($fe, "trim(@$zeit@) !=='' and trim(@$event@) !== '' and trim(@$gruppe@) !== ''");
			
			$f = $p."km2.dat"; export::asc($fe, $f);
			$l1 = label::v($zeit);
			$l2 = label::v($event);
			$la = label::v($gruppe); $stf = data::uvstufen($fe, $gruppe); $cl = label::codes($fe, $gruppe);
			
			$r .= "options(warn = -1, 'scipen' = 100, 'digits' = 4); library(ggplot2); library(survival); library(scales); source('/mnt/69/eigenes/commandr/functions.r');".$l;
			$r .= "x <- read.table('$f', header = T, fill = T, row.names = NULL); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
			
			$r .= "gr = length(unique(x\$$gruppe)); xl = c($xlim)[1:2]; yl = c($ylim)[1:2]; xbr = c($xlim)[3]; ppos = c($ppos); tpos = c($tpos); lpos = c($lpos);".$l;
			for($j = 0; $j < count($stf); ++$j){
				$u = $stf[$j]; $ul = label::c($gruppe, $u); $stfl[$j] = $ul;
				
				$r .= $l."fit <- survfit(Surv($zeit, $event) ~ $gruppe, subset(x, $gruppe %in% $u), conf.type = 'plain');
				fit2 <- data.frame($gruppe = '$u', time = fit\$time, surv = fit\$surv, nrisk = fit\$n.risk, nevent = fit\$n.event, ncensor = fit\$n.censor, lower = round(fit\$lower, 2), upper = round(fit\$upper, 2), ".$gruppe."_l = '$ul');
				fit2\$surv = round(fit2\$surv, 2);
				ncensor <- subset(fit2, ncensor != 0);
				q$j <- data.frame(quantile(fit, quantiles = c(0.25, 0.5, 0.75)));
				r0 <- fit2[1, ]; r0[1, 2] <- 0; r0[1, 3] <- 1; r0[1, 5] <- 0; r0[1, 6] <- 0; fit2 <- rbind(r0, fit2);".$l;
				
				if ($j == 0) $r .= "s  <- fit2;".$l;    else $r .= "s <- rbind(s, fit2);".$l;
				if ($j == 0) $r .= "c  <- ncensor;".$l; else $r .= "c <- rbind(c, ncensor);".$l.$l;
			}
			
			$ar = "family = 'arial'"; $cou = "family = 'courier'";
			$r .= "png(file = '$ofi.png');".$l;
			
			$sh = "0.001"; $li = "longdash"; $si = 0.15; $c = "colour = 'grey35'";
			$r .= "co1 = c('red', 'blue', 'green', 'cyan', 'black', 'blueviolet', 'grey40', 'grey60', 'yellow');".$l; #http://research.stowers-institute.org/efg/R/Color/Chart/
			if ($this->vonunten) $vu = "1 - "; else $vu = "";
			if ($this->CI) $ci = "1 == 1"; else $ci = "1 == 2";
			if ($this->plottab) $pt = "1 == 1"; else $pt = "1 == 2";
			$r .= "
				palette <- c('red', 'blue', 'green', 'yellow', 'black', 'grey30', 'grey50', 'grey70', 'grey90')
				#				  1 - 
				gg1 <- ggplot(s, aes(y = $vu surv, x = time, colour = factor(".$gruppe.") )) + geom_step(size = 1.5) + 
					geom_hline(yintercept = 0, colour = 'grey70', size = .5) + xlab('$l1') + ylab('$l2') + 
					geom_vline(xintercept = 0, colour = 'grey70', size = .5) + 
					geom_point(data = c, size = 1.5, shape = 22, fill = 'white') + 
					scale_y_continuous(limits = yl, breaks = seq($ylim), labels = function(x) sprintf('%.0f%%', x * 100)) + 
					scale_x_continuous(limits = xl, breaks = seq($xlim)) +
					scale_colour_manual(values = palette, breaks = c(".fins($stf)."), labels = c($cl)) +
					theme(legend.title = element_blank(), legend.text = element_text(size = 14), 
						legend.background = element_rect(fill = alpha('grey70', 0)), 
						legend.justification = c('left', 'bottom'), legend.position = c(lpos[1], lpos[2]),
						axis.text.x = element_text(size = 10, colour = 'black'), axis.text.y = element_text(size = 10, colour = 'black')
					);
				
				# ____________________________ 95%-CI in pastell ____________________________
				#						1 -               1 -
				if ($ci) gg1 <- gg1 + geom_ribbon(aes(ymax = $vu upper, ymin = $vu lower, fill = ".$gruppe."), show.legend = F, alpha = 0.1, colour = NA) + scale_fill_manual(values = co1);
					
				# _______________________________ Tab im Plot _______________________________
				if ($pt){
					library(ggpmisc, warn.conflicts = F);
					fit <- survfit(Surv($zeit, $event) ~ 1, x, conf.type = 'plain');
					a = data.frame(t(summary(fit)\$table));
					
					if (gr > 1){
						fit <- survfit(Surv($zeit, $event) ~ $gruppe, x, conf.type = 'plain');
						a1 = data.frame(summary(fit)\$table);
						a = rbind(a1, a);
					}
					
					a\$gruppe = gsub('.+[=]', '', row.names(a));
					a\$gruppe[length(a\$gruppe)] = 9999;
					a\$gruppe <- factor(a\$gruppe, levels = c(".fins($stf).",9999), labels = c('".fins($stfl, "','")."', 'total'));
					
					names(a) <- gsub('records', 'n', names(a));
					names(a) <- gsub('.+se.+rmean.+', 'SE', names(a));
					names(a) <- gsub('.+rmean', 'mean\nsurv.', names(a));
					names(a) <- gsub('.+95LCL', 'lower\nCI', names(a));
					names(a) <- gsub('.+95UCL', 'upper\nCI', names(a));
					names(a) <- gsub('median', 'median\ntime', names(a));
					
					a\$'mean\nsurv.' = round(a\$'mean\nsurv.', 1);
					a\$SE = round(a\$SE, 1);
					a\$'lower\nCI' = round(a\$'lower\nCI', 1);
					a\$'upper\nCI' = round(a\$'upper\nCI', 1);
					a\$'median\ntime' = round(a\$'median\ntime', 0);
					a[is.na(a)] = '';
					
					#a = get(a, '^gr,^n$,ev,med,mean,^SE,CI');
					a = get(a, '^gr,^n$,ev,mean,^SE');
					names(a) <- gsub('surv.', 'time', names(a));
					names(a) <- gsub('gruppe', ' ', names(a));
					
					gg1 = gg1 + annotate(geom = 'table', hjust = 0, vjust = 0, size = 4, x = tpos[1], y = tpos[2], label = list(a), parse = F);
				}
				
				
				# _______________________________ p des logrank _______________________________
				st = sort(unique(x\$$gruppe)); z = 0; b0 = ' ';
				if (length(st) >= 2){
					l <- survdiff(Surv($zeit, $event) ~ $gruppe, x);
					pval <- pchisq(l\$chisq, length(l\$n) - 1, lower.tail = FALSE);
					pval <- ifelse(pval < 0.0001, 'p < 0.0001', sprintf('p = %.4f', pval));
					for (i in 1 : length(st)) {
						for (j in 1 : length(st)) {
							if (i < j) {
								x2 = subset(x, $gruppe %in% c(st[i], st[j]));
								l = survdiff(Surv($zeit, $event) ~ $gruppe, x2);
								pval2 <- pchisq(l\$chisq, length(l\$n) - 1, lower.tail = FALSE);
								z = z + 1; b = '';
								if (pval2 <= 0.05) b = intToUtf8(96 + z);
								pval = pa2(pval, b0, b); b0 = '';
							}
						}
					}
					gg1 <- gg1 + annotate('text', hjust = 0, vjust = 0, x = ppos[1], y = ppos[2], label = paste('log-rank-Test: ', pval), size = 4);
				}
				
				
				# _______________________________ Varianten des logrank _______________________________
				if (1 == 2){
					library(survminer);
					fit <- survfit(Surv($zeit, $event) ~ $gruppe, data = x);
					p1 = surv_pvalue(fit, x, '1')\$pval.txt;
					p2 = surv_pvalue(fit, x, 'n')\$pval.txt;
					p3 = surv_pvalue(fit, x, 'sqrtN')\$pval.txt;
					p4 = surv_pvalue(fit, x, 'S1')\$pval.txt;
					p5 = surv_pvalue(fit, x, 'S2')\$pval.txt;
					p6 = surv_pvalue(fit, x, 'FH_p=1_q=1')\$pval.txt;
					
					#x\$treat[x\$treat == 2] = 0; 
					n <- nrow(x);
					c = coxph(formula = Surv($zeit, $event) ~ $gruppe, x);
					c2 = data.frame(cbind(summary(c)\$coefficients, summary(c)\$conf.int, n))[, c(5,6,8,9,1,3,4,10)];
					
					hr = c2[, 2]; lo_hr = c2[, 3]; up_hr = c2[, 4];
					f = '%##.3f';
					hr = sprintf(hr, fmt = f);
					lo_hr = sprintf(lo_hr, fmt = f);
					up_hr = sprintf(up_hr, fmt = f);
					hr = pa2('Hazard Ratio = ', hr, ' (95%-CI von ', lo_hr, ' bis ', up_hr, ', n = ', n, ')');
					
					pval = pa2('\n', 
							p1, ', Mantel-Cox (sensitive for late differences)\n', 
							p2, ', Gehan-Wilcoxon (sensivite for early differences)\n',
							p3, ', Tarone-Ware (sensivite for early differences)\n',
							p4, ', Peto & Peto (sensivite for early differences, more robust)\n',
							hr);
					pval = '';
					
					st = sort(unique(x\$$gruppe)); z = 0;
					if (length(st) >= 2){
						for (i in 1 : length(st)) {
							for (j in 1 : length(st)) {
								if (i < j) {
									x2 = subset(x, $gruppe %in% c(st[i], st[j]));
									l = survdiff(Surv($zeit, $event) ~ $gruppe, x2);
									pval2 <- pchisq(l\$chisq, length(l\$n) - 1, lower.tail = FALSE);
									
									c = coxph(formula = Surv($zeit, $event) ~ $gruppe, x2);
									hr = coef(summary(c))[,2];
									se = coef(summary(c))[,3]; 
									lo_hr = hr - 1.96 * se; up_hr = hr + 1.96 * se; f = '%##.3f';
									hr = sprintf(hr, fmt = f);
									lo_hr = sprintf(lo_hr, fmt = f);
									up_hr = sprintf(up_hr, fmt = f);

									z = z + 1; b = '';
									if (pval2 <= 0.05) b = intToUtf8(96 + z); b = '';
									if (1 == 1) pval = pa2(pval, '\n', labelc('$gruppe', st[i], '$db'), ' vs ', labelc('$gruppe', st[j], '$db'), ': ', sign2(pval2, 3), ', HR = ', hr, ' [', lo_hr, ' - ', up_hr, ']');
									if (1 == 2) pval = pa2(pval, ' ', b);
								}
							}
						}
					}
					
					gg1 <- gg1 + annotate('text', hjust = 0, vjust = 0, x = ppos[1], y = ppos[2], label = paste('log-rank-Test: ', pval), size = 4);
				}
				
				# _______________________________ tab unter dem plot _______________________________
				if (1 == 2){
					s['timek'] <- trunc(s\$time / xbr) * xbr;
					
					mx <-aggregate(s\$nrisk , by = list(s\$$gruppe, s\$timek), max2); colnames(mx) <- c('$gruppe', 'timek', 'nrisk' );
					ev <-aggregate(s\$nevent, by = list(s\$$gruppe, s\$timek), sum2); colnames(ev) <- c('$gruppe', 'timek', 'nevent');
					ce <-aggregate(s\$ncens , by = list(s\$$gruppe, s\$timek), sum2); colnames(ce) <- c('$gruppe', 'timek', 'ncens' );
					o1 <- merge(mx, ev, by = c('$gruppe', 'timek'));
					
					o2 <- merge(o1, ce, by = c('$gruppe', 'timek'));
					o3 <- o2[order(o2\$$gruppe, o2\$timek),];
					
					o3 = subset(o3, complete.cases(o3));
					o3\$$gruppe <- as.numeric(o3\$$gruppe);
					o3\$gr = as.numeric(as.character(o3\$$gruppe));
					
					l = length(unique(o3\$$gruppe)); gr = l * 3;
					co2 = co1[1:l];
					
					o3['gruppe1'] = (o3\$gr - 1) * 3 + 1;
					o3['gruppe2'] = o3\$gruppe1 + 1;
					o3['gruppe3'] = o3\$gruppe2 + 1;
					
					o3a = o3[, c('gruppe1', 'timek', 'nrisk') ]; colnames(o3a) <- c('$gruppe', 'timek', 'value');
					o3b = o3[, c('gruppe2', 'timek', 'ncens') ]; colnames(o3b) <- c('$gruppe', 'timek', 'value');
					o3c = o3[, c('gruppe3', 'timek', 'nevent')]; colnames(o3c) <- c('$gruppe', 'timek', 'value');
					
					o4 <- rbind(o3a, o3b, o3c);
					o4 = o4[with(o4, order($gruppe, timek)),];
					
					lb = c('at risk', 'obs. end', 'events');
					mn = min(o4\$$gruppe);
					mx = max(o4\$$gruppe);
					
					gg2 = ggplot(o4, aes(x = timek, y = $gruppe, label =  value)) + geom_text(size = 3.5) + ylab(NULL) + xlab(NULL) + 
						scale_x_continuous(limits = xl, breaks = seq($xlim)) + 
						scale_y_reverse(breaks = seq(mn,mx), labels = c(rep(lb, l)) ) + 
						theme(axis.text.x = element_blank(), axis.ticks = element_blank(), axis.text.y = element_text(size = 9, colour = rep(co2, each = 3 )),
							panel.grid.major.y = element_line(colour = 'grey90', size = 6, linetype = 'solid'), 
							panel.grid.major.x = element_blank(), panel.grid.minor.x = element_blank());
							
					library(gridExtra); library(gtable);
					gg1a = ggplot_gtable(ggplot_build(gg1));
					gg2a = ggplot_gtable(ggplot_build(gg2));
					gg2a\$widths = gg1a\$widths;
					gg1 = grid.arrange(gg1a, gg2a, nrow = 2, ncol = 1,  heights = unit(c(8, gr * .55), c('null', 'null'))  );
				}
				ggsave(plot = gg1, file = '$ofi.png', dpi = 300, device = 'png'); rm(gg1); rm(gg2); rm(gg1a); rm(gg2a); rm(gg3);
				
				pr(traceback());".$l;
			
			$lg = "/eigenes/downs/temp/Rserve.log"; exec("truncate -s 0 $lg"); 
			
			$fi = "/eigenes/www/$db/out/km4.r"; write2($r, $fi); 
			#exec("sudo Rscript --no-init-file --no-save --no-restore --verbose '$fi' > /eigenes/downs/temp/Rserve.log 2>&1 &"); 
			$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
		}
		
		$fi = fromto($ofi, $db."/", "").".png";
		$h = 700; echo $l.$l."<table border = 0 id = toptab><tr><td height = $h width = $h><img src = '$fi' style = 'height: 95%; width: 95%;'/></td><tr></table>";
			
		echop("<br><textarea id = mytextarea rows = 10 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $r)."</textarea> ");
		
		$te = read2($lg);
		echop("<br><textarea id = mytextarea2 rows = 8 cols = 130 wrap = off>".preg_replace("/(\t){1,}/", "", $te)."</textarea> ");
		echo "<script language = javascript>ta = document.getElementById('mytextarea2'); ta.scrollTop = ta.scrollHeight; </script>";
	}
}


class label {

	// md(label::c("gruppe", 1));
	function v($v){
		$q = "select varlabel from labels where var = '$v' limit 1 ";
		$l = mysqli_fetch_row(myq($q))[0];
		
		$l = preg_replace("/_@k@_/", ",", $l);
		$l = self::um($l);
		
		if ($l == "" and $l !== "(leer)") return $v; else if ($l == "(leer)") return ""; else return $l;
		if ($l == "") return $v;
	}
	
	function c($v, $c){
		$q = "select codelabel from labels where var = '$v' and code = '$c' limit 1";
		$l = mysqli_fetch_row(myq($q))[0];
		$l = self::um($l);
		$l = preg_replace("/_@k@_/", ",", $l);
		if ($l == "") return $c; else return $l;
	}
	
	function set($v){
		$q = "select varset from labels where var = '$v' limit 1";
		$l = mysqli_fetch_row(myq($q))[0];
		if ($l == "") $l = self::v($v);
		if ($l == "") return $v; else return $l;
	}
	
	function u($v){
		$q = "select unit from labels where var = '$v' limit 1";
		$l = mysqli_fetch_row(myq($q))[0];
		if ($l == "") return $v; else return $l;
	}
	
	function g($v){
		$q = "select gender from labels where var = '$v' limit 1";
		$l = mysqli_fetch_row(myq($q))[0];
		if ($l == "") return $v; else return $l;
	}
	
	function codes($fe, $uv, $gaense = 1){
		$uv = implode(",", data::vl($fe, $uv));
		$st = data::uvstufen($fe, $uv);
		for ($j = 0; $j < count($st); ++$j) $o[$j] = label::c($uv, $st[$j]);
		if ($gaense == 1) return "'".implode("','", $o)."'"; else return implode(",", $o);
	}
	
	function headers($fe){
		foreach ($fe as $key => $value) $fe2[self::v($key)] = $value;
		return $fe2;
	}
	
	function col($fe, $col, $uv){
		for ($i = 0; $i < count($fe[$col]); ++$i) $fe[$col][$i] = givelabel($uv, $fe[$col][$i]);
		return $fe;
	}
	
	function col0($fe){
		$n = array_keys($fe);
		for ($i = 0; $i < count($fe[$n[0]]); ++$i) $fe[$n[0]][$i] = label::v($fe[$n[0]][$i]);
		return $fe;
	}
	
	function liste($arr){
		for ($j = 0; $j < count($arr); ++$j) $arr[$j] = self::v($arr[$j]);
		return $arr;
	}
	
	function um($s){
		$s = preg_replace("/ae/", "ä", $s); $s = preg_replace("/Ae/", "Ä", $s);
		$s = preg_replace("/ue/", "ü", $s); $s = preg_replace("/Ue/", "Ü", $s);
		$s = preg_replace("/oe/", "ö", $s); $s = preg_replace("/Oe/", "Ö", $s);
		$s = preg_replace("/Fraün/", "Frauen", $s);
		
		$s = preg_replace("/_pm_/", str::plusminus(), $s);
		$s = preg_replace("/_h3_/", str::hoch3(), $s);
		$s = preg_replace("/_d_/", "Δ", $s);
		$s = preg_replace("/_ge_/", "≥", $s);
		$s = preg_replace("/_le_/", "≤", $s);
		
		return $s;
	}
}



class mysql {

	function q($q){
		return mysqli_fetch_row(myq($q))[0];
	}
	
	function err(){
		$e = mysqli_error(); if ($e <> "") echo($e);
	}
	
	// echo mysql::rec("select varlabel from labels where var = 'k5'");
	function rec($s){
		return mysqli_fetch_row(self::q($s))[0];
	}
	
	function dropt($t){
		$db = currdb();
		$q = "select table_name from information_schema.tables where table_schema = '$db' and instr('$tb', table_name)";
		$rs = myq($q);
		while($row = mysqli_fetch_row($rs)) for ($i = j; $j < count($t); ++$j) {md ("...dropping ".$row[0]); myq("drop table ".$row[0]);}
	}
	
	function exists($tb){
		$t = recwert("show tables like $tb");
		if (trim($t) !== "") return true; else return false;
	}
	
	function col($tb, $col){
		$exists = false;
		$rs = myq("show columns from $tb");
		while($row = mysqli_fetch_row($rs)) if ($row[0] == trim($col)) $exists = true;
		return $exists;
	}
	
	function index($tb, $ind){
		$db = currdb();
		#md ("select * from information_schema.statistics where table_schema = $db and table_name = $tb and column_name = $ind");
		$r = recwert("SHOW KEYS FROM $tb WHERE Key_name = '$ind'");
		if ($r == "") return false; else return true;
	}
	
	function getcols($tb){
		$fe = import::mysql("show columns from ".$tb); 
		return fins($fe["Field"]);
	}
	
	function colformat($tb,$c){
		$db = currdb();
		$fe = import::mysql("select column_name, data_type from information_schema.columns where table_schema = '$db' and table_name = '$tb' and column_name = '$c'"); 
		#show3($fe);
		return $fe["data_type"][0];
	}
	
	
	function zzz(){
		/*
		if (file_exists($f)) exec("rm $f");
	
		$rs  = myq($sql);
		$gr1 = mysqli_num_fields($rs);

		$fp = fopen($f, "a");
		for ($j = 0; $j < $gr1; ++$j){
			$l = mysqli_field_name($rs, $j);
			$l = str_replace(chr(10),"",$l);
			$l = str_replace(chr(13),"",$l);
			$l = chr(34).$l.chr(34);
			if ($j < $gr1 - 1) $l = $l.","; else $l = $l."\n";
			fwrite($fp, $l);
		}
	
		while($row = mysqli_fetch_array($rs)) {
			$l0 = $row[1];
			for ($j = 0; $j < $gr1; ++$j){
				$l = $row[$j];
				$l = str_replace(chr(8), "", $l);
				$l = str_replace(chr(10), "", $l);
				$l = str_replace(chr(13), "", $l);
				$l = str_replace(chr(15), "", $l);
				$l = str_replace(chr(34), "", $l);
				$l = str_replace(chr(39), "", $l);
				$l = str_replace(",", "", $l);
				$l = str_replace(";", "", $l);
				$l = chr(34)."".$l.chr(34);
				if ($j < $gr1 - 1) $l = $l.","; else $l = $l."\n";
				fwrite($fp, $l);
			}
		}
		fclose($fp);
		//*/
	
		#md(88887);
	}

}

class str {

	function numeric($s){
 		for ($i = 0; $i < strlen($s); ++$i) $z .= (ord(substr($s, $i, 1)) - 96);
		return $z;
	}
	
	function hoch($v){
		return "^".preg_replace("/,/","$,^",$v)."$";
	}
	
	//md(str::zufall(3));
	function zufall($lae){
		return substr(md5(rand()), 0, $lae);
	}
	
	function fe2str($fe){
		$kf = array_keys($fe);
		for($i = 0; $i < count($fe[$kf[0]]); ++$i){
			unset($q);
			if ($i == 0) $t .= implode(chr(9), $kf).chr(10);
			for($j = 0; $j < count($kf); ++$j) $q[$j] = $fe[$kf[$j]][$i];
			$t .= implode(chr(9), $q).chr(10);
		}
		return $t;
	}
	
	function str2fe($s, $tx1 = "\\n", $tx2 = "\t"){
		$fe1 = explode($tx2, $s);
		for($i = 0; $i < count($fe1); ++$i) $fe2[$i] = explode($tx1, $fe1[$i]);

		for ($j = 0; $j < count($fe2[0]); ++$j){
			$v = "v".$j; $z = -1;
			for ($i = 0; $i < count($fe2); ++$i) {++$z; $o[$v][$z] = $fe2[$i][$j];}
		}
		return $o;
	}
	
	function plusminus(){return "±";}
	function hoch3(){return "³";}
	
	
}

class num {

	function format($s, $dez){
		$s = trim($s);
		if ($s !== "" and is_numeric($s)) return number_format($s, $dez);
	}

	function trunc($number, $dez = 0) {
		$shift = pow(10, $dez);
		return intval($number * $shift) / $shift;
	}
}

class time {

	// $st = time::start(); md(time::end($st));
	function start(){
		return microtime(true);
	}

	function end($st, $unit = "s"){
		return(format2(microtime(true) - $st, "0.000")." ".$unit);
	}

}

function monname($nr){
	if ($nr ==  1) return "Januar";
	if ($nr ==  2) return "Februar";
	if ($nr ==  3) return "März";
	if ($nr ==  4) return "April";
	if ($nr ==  5) return "Mai";
	if ($nr ==  6) return "Juni";
	if ($nr ==  7) return "Juli";
	if ($nr ==  8) return "August";
	if ($nr ==  9) return "September";
	if ($nr == 10) return "Oktober";
	if ($nr == 11) return "November";
	if ($nr == 12) return "Dezember";
}

function monnr($mon){
	if ($mon == "Jan") return 1;
	if ($mon == "Feb") return 2;
	if ($mon == "Mar") return 3;
	if ($mon == "Apr") return 4;
	if ($mon == "May") return 5;
	if ($mon == "Jun") return 6;
	if ($mon == "Jul") return 7;
	if ($mon == "Aug") return 8;
	if ($mon == "Sep") return 9;
	if ($mon == "Okt") return 10;
	if ($mon == "Nov") return 11;
	if ($mon == "Dec") return 12;
}

class xls {
	function show($f){
		$ty = \PhpOffice\PhpSpreadsheet\IOFactory::identify($f);
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ty);
		$spreadsheet = $reader->load($f);
		$fe = $spreadsheet->getActiveSheet()->toArray();
		echo "<table border = 0>";
		foreach($fe as $col){
			echo "<tr>";
			foreach($col as $cell) echo "<td>".$cell."</td>";
			echo "</tr>";
		}
		echo "<tr></table>";
	}

}

class strategy {

	function kmmultiple($fe, $binav, $time){
		$fe1 = data::rnd(550, 2, 0,  1); $fe1 = data::rename($fe1, "/^c(.+)/", "b\$1");
		$fe2 = data::rnd(550, 2, 0, 20); $fe2 = data::rename($fe2, "/^c(.+)/", "t\$1");
		$fe = data::merge(array($fe1, $fe2), "lfn"); show3($fe);
		$fe = data::varstocases($fe, array("^b", "^t"), "^lfn$", array("b", "t"));
		$fe = data::comp($fe, "@gr@ = @recnum@;");
		km(1, flip3($fe), "t", "b", "gr", "0, 22, 2", "0, 1, .2");
	}
	
	function implement_test(){
		/*
		interface table2 {}
		class freq implements table2 {
			function __construct($fe, $v) {$this->fe = $fe; $this->v = $v;}
			function show(){table::freq($this->fe, $this->v);}
		}
		class means implements table2 {
			function __construct($fe, $v, $fu) {$this->fe = $fe; $this->v = $v; $this->fu = $fu;}
			function show(){table::desc($this->fe, $this->v);}
		}
		function make(table2 $obj) {return $obj->show();}
		make(new means($fe, "c1"));
		make(new freq ($fe, "k1"));	
		//*/
	}
	
	function nachbau_der_dissertation() {
		$gr = 2;
		$id = 3;
		$mw = 2;
		$beobgr = 10;
		$iv = 30;
		$codecount = 4;
		
		$z = -1;
		for ($g = 1; $g <= $gr; ++$g) {
			for ($x = 1; $x <= $id; ++$x) {
				for ($y = 1; $y <= $mw; ++$y) {
					for ($i = 0; $i < $beobgr; ++$i) {
						++$z;
						$fe["gr"   ][$z] = $g;
						$fe["id"   ][$z] = $x;
						$fe["mw"   ][$z] = $y;
						$fe["zeit1"][$z] = mt_rand(0, $iv); $fe["code1"][$z] = mt_rand(1, $codecount);
						$fe["zeit2"][$z] = mt_rand(0, $iv); $fe["code2"][$z] = mt_rand(1, $codecount);
					}
				}
			}
		}
		$d = new data;
		$fe1 = $d::agg($fe, "code1", "gr,id,mw,zeit1", "max2"); $fe1 = data::rename($fe1, array("/zeit1/", "/_max2/"), array("zeit", ""));
		$fe2 = $d::agg($fe, "code2", "gr,id,mw,zeit2", "max2"); $fe2 = data::rename($fe2, array("/zeit2/", "/_max2/"), array("zeit", ""));
		#show3($fe); die;
		
		$z = -1;
		for ($g = 1; $g <= 2; ++$g) {
			for ($x = 1; $x <= $id; ++$x) for ($y = 1; $y <= $mw; ++$y) for ($i = 0; $i <= $iv; ++$i) {++$z; $sc["gr"][$z] = $g; $sc["id"][$z] = $x; $sc["mw"][$z] = $y; $sc["zeit"][$z] = $i;}
		}
		#show3($sc); die;
		$fe = $d::merge(array($sc, $fe1, $fe2), "gr,id,mw,zeit");
		#show3($fe); die;
		
		$fe = $d::comp($fe, "@base1@ = @code1@;");
		$fe = $d::comp($fe, "@base2@ = @code2@;");
		$kf = array_keys($fe);
		for ($i = 0; $i < count($fe[$kf[0]]); ++$i) {
			$x = "id"; $m = "mw";
			if ($fe[$x][$i] == $fe[$x][$i - 1] and $fe[$m][$i] == $fe[$m][$i - 1] and $fe[$c][$i] == ""){
				$c = "code1"; $fe[$c][$i] = $fe[$c][$i - 1];
				$c = "code2"; $fe[$c][$i] = $fe[$c][$i - 1];
			}
		}
		$fe = lfn3($fe);
		#show3($fe); die;
		
		for ($j = 1; $j <= $codecount; ++$j) {
			$jf = format2($j, "0");
			$v = "code1"; $fe = $d::comp($fe, "if (@$v@ == $j) @".$v.$jf."@ = 1;");
			$v = "code2"; $fe = $d::comp($fe, "if (@$v@ == $j) @".$v.$jf."@ = 1;");
			$v = "base1"; $fe = $d::comp($fe, "if (@$v@ == $j) @".$v.$jf."@ = 1;");
			$v = "base2"; $fe = $d::comp($fe, "if (@$v@ == $j) @".$v.$jf."@ = 1;");
		}
		$fe = lfn3($fe);
		$fe = $d::rename($fe, array("/code/", "/base/"), array("c", "b"));
		#show3($fe); die;
		
		$fe = $d::agg($fe, "^c[12][\d\d\d],^b[12][\d\d\d]", "gr,id", "sum2");
		$fe = $d::rename($fe, "/(.+)_sum2/", "\$1");
		show3($fe); #die;
		
		table::reptab_means($fe, "^c1,^c2", "^gr$");
		table::reptab_means($fe, "^b1,^b2", "^gr$");
	}
}
	

class validate {

	#$fe1 = data::rnd(5, 5, 1, 9); $fe1 = data::comp($fe1, "is_even(@lfn@) ? @gr@ = 1 : @gr@ = 0;"); $fe2 = $fe1; $fe2 = data::comp($fe2, "if (@c01@ < 5) @c01@ = '99';"); $fe2 = data::comp($fe2, "if (@c03@ < 5) @c03@ = '99';");
	#validate::compare($fe1, $fe2);
	function compare($fe1, $fe2){
		while (list($k, $i) = each($fe1)) for ($i = 0; $i < count($fe1[$k]); ++$i) if ($fe1[$k][$i] !== $fe2[$k][$i]) $fe2[$k][$i] = "<b><font color = red>".$fe2[$k][$i]."</font></b>";
		md("Matrix 1");
		show3($fe1);
		md("Matrix 2 = rot = es hat sich was geändert im Vergleich zur Matrix 1");
		show3($fe2);
	}
}

class show {
	
	function __construct() {
		$this->align = "1,3";
		$this->cols = "100,50";
		#$this->borders = "^0$:.:s_o s_u,^last$:.:s_u s_o,2 to lastbut1:.:d_o";
		if ($this->borders == "") $this->borders = "^0$|total:.:s_o s_u,^last$:.:s_u s_o,2 to lastbut1:.:d_o";
		$this->row0insert = "";
		$this->save = "";
		$this->firstrow = "";
	}

	// $fe = data::rnd(15, 2); show::fe($fe);
	function fe($fe){
		$kf = array_keys($fe);
		$gr1 = count($fe[$kf[0]]);
		$gr2 = count($kf);
		$l = chr(10);
		$rf = explode(",", $this->borders);
		$wf = explode(",", $this->cols);
		$al = explode(",", $this->align); $alf = array("", "ll", "cc", "rr"); for ($j = 0; $j < $gr2; ++$j) {$al[$j] = $alf[$al[$j]]; if ($al[$j] == "") $al[$j] = $al[$j - 1];}
		for ($j = 0; $j <  $gr2; ++$j) array_unshift($fe[$kf[$j]], $kf[$j]);
		for ($i = 0; $i <= $gr1; ++$i) {
			for ($j = 0; $j < $gr2; ++$j) {
				unset($clf);
				for ($k = 0; $k < count($rf); ++$k) {
					$r1 = explode(":", $rf[$k]);
					$r1[0] = preg_replace("/lastbut1/", $gr1 - 1, $r1[0]); $r1[1] = preg_replace("/lastbut1/", $gr2 - 1, $r1[1]);
					$r1[0] = preg_replace("/last/"    , $gr1 - 0, $r1[0]); $r1[1] = preg_replace("/last/"    , $gr2 - 0, $r1[1]);
				
					$r1[0] = preg_replace("/,/", "|", kontrolliere($r1[0], 1));
					$r1[1] = preg_replace("/,/", "|", kontrolliere($r1[1], 1));
					
					$mi1a = preg_match("/".$r1[0]."/", $i); $mi1b = preg_match("/".$r1[0]."/", $fe[$kf[0]][$i]); 
					$mj1a = preg_match("/".$r1[1]."/", $j); $mj1b = preg_match("/".$r1[1]."/", $fe[$kf[0]][$i]);
					
					if ($mi1a == 1 and $mj1a == 1 or $mi1b == 1 and $mj1b == 1) if (!is_array($clf)) $clf[] = $r1[2];
				}
				$cl = implode(" ", array_unique($clf));
				if ($st[$i][$j] == "") $st[$i][$j] = $cl;
			}
		}
		
		$na = "tb_".zufallsstring(3);
		$t = $l.$l."<table id = $na border = 0 style = 'background-color:#E6E6E6;' >".$l;
		$t .= $this->firstrow;
		for ($j = 0; $j <  $gr2; ++$j) {$w = $wf[$j]; if ($wf[$j] == "") $w = $wf[count($wf) - 1]; $t .= "<col width = '".$w."px'>".$l; }
		for ($i = 0; $i <= $gr1; ++$i) {
			$t .= "<tr id = zei".sprintf('%05d', $i).">";
			for ($j = 0; $j < $gr2; ++$j) {
				$lb = $kf[$j]; if ($lb == "") $lb = "col".$j;
				if ($st[$i][$j] !== "") $cl = "class = '".$al[$j]." ".$st[$i][$j]."'"; else $cl = "";
				$t .= "<td $cl id = '".$lb.$i."' >".$fe[$kf[$j]][$i]."</td>";
			}
			$t .= "</tr>".$l;
		}
		$t .= "</table>".$l.$l;
		echop($t);
		if ($this->save !== "") write2($t, $this->save.".html");
	}
	
	// $fe = data::rnd(15, 2); show::fe($fe); $s->fe0(flip3($fe));
	function fe0($fe){	// altes Format
		$l = chr(10);
		$gr1 = count($fe);
		$gr2 = count($fe[0]);
		
		$rf = explode(",", $this->borders);
		$wf = explode(",", $this->cols);
		$al = explode(",", $this->align); $alf = array("", "ll", "cc", "rr"); for ($j = 0; $j < $gr2; ++$j) {$al[$j] = $alf[$al[$j]]; if ($al[$j] == "") $al[$j] = $al[$j - 1];}
		
		for ($i = 0; $i <= $gr1; ++$i) {
			for ($j = 0; $j < $gr2; ++$j) {
				unset($clf);
				for ($k = 0; $k < count($rf); ++$k) {
					$r1 = explode(":", $rf[$k]);
					$r1[0] = preg_replace("/lastbut1/", $gr1 - 1, $r1[0]); $r1[1] = preg_replace("/lastbut1/", $gr2 - 1, $r1[1]);
					$r1[0] = preg_replace("/last/"    , $gr1 - 0, $r1[0]); $r1[1] = preg_replace("/last/"    , $gr2 - 0, $r1[1]);
				
					$r1[0] = preg_replace("/,/", "|", kontrolliere($r1[0], 1));
					$r1[1] = preg_replace("/,/", "|", kontrolliere($r1[1], 1));
					
					$mi = preg_match("/".$r1[0]."/", $i);
					$mj = preg_match("/".$r1[1]."/", $j);
					if ($mi == 1 and $mj == 1) $clf[] = $r1[2];
				}
				$cl = implode(" ", array_unique($clf));
				if ($st[$i][$j] == "") $st[$i][$j] = $cl;
			}
		}
		
		$na = "tb_".zufallsstring(3);
		$t = $l.$l."<table id = $na border = 0 style = 'background-color:#E6E6E6;' >".$l;
		$t .= $this->firstrow;
		for ($j = 0; $j <  $gr2; ++$j) {$w = $wf[$j]; if ($wf[$j] == "") $w = $wf[count($wf) - 1]; $t .= "<col width = '".$w."px'>".$l; }
		for ($i = 0; $i <  $gr1; ++$i) {
			$t .= "<tr id = zei".sprintf('%05d', $i).">";
			for ($j = 0; $j < $gr2; ++$j) {
				$lb = $kf[$j]; if ($lb == "") $lb = "col".$j;
				if ($st[$i][$j] !== "") $cl = "class = '".$al[$j]." ".$st[$i][$j]."'"; else $cl = "";
				$t .= "<td $cl id = '".$lb.$i."' >".$fe[$i][$j]."</td>";
			}
			$t .= "</tr>".$l;
		}
		$t .= "</table>".$l.$l;
		echop($t);
		#if ($this->save !== "") write2($t, $this->save.".html");
	}	
	
	// $fe = data::rnd(15, 2); $f = "/tmp/tmp.xls"; export::xls($fe, $f, $cols = "5", $pos = "2", $fo = "@,0"); show::xls($f);
	function xls($f){
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
            $objPHPExcel = $objReader->load($f);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
            $objWriter->save('php://output');
	}
	
	// show::mysql("test");
	function mysql($t){
		self::fe(import::mysql("select * from ".$t));;
	}
	
	// show3($s->labels($fe));
	function labels($fe){
		$kf = array_keys($fe); $v0 = $kf[0];
		for ($j = 0; $j < count($fe); ++$j){
			$v = $kf[$j];
			for ($i = 0; $i < count($fe[$v0]); ++$i) $fe[$v][$i] = label::c($v, $fe[$v][$i]);
		}
		return($fe);
	}
	
}

class write {
	
	function fe($fe, $ofi){
		$kf = array_keys($fe);
		
		for ($i = 0; $i < count($fe[$kf[0]]); ++$i){
			for ($j = 0; $j < count($kf); ++$j) $o[$i][$j] = $fe[$kf[$j]][$i];
			$o1[] = implode(chr(9), $o[$i]);
		}
		$o2 = implode(chr(9), $kf).chr(10).implode(chr(10), $o1);
		fwrite(fopen($ofi, "w"), $o2);
	}
	
	function fe0($fe, $ofi, $gaense = 0){ // altes Format mit Header
		$g = chr(34);
		$gr1 = count($fe);
		$gr2 = count($fe[0]);
		for ($i = 0; $i < $gr1; ++$i){
			unset($t);
			for ($j = 0; $j < $gr2; ++$j){
				$w = $fe[$i][$j]; $w = preg_replace("/\\n/", "", $w); $t[] = $w;
			}
			if ($gaense == 0) $s[] = implode(chr(9), $t); else $s[] = $g.implode($g.chr(9).$g, $t).$g; 
		}
		$o = implode(chr(10), $s);
		fwrite(fopen($ofi, "w"), $o);
	}
}

class mcgee { //vergleicht Listen wie McGee von NCIS

	/*for ($j = 0; $j < 5; ++$j){
		$fe["lfn"][$j] = $j;
		$fe["nr"][$j] = '9'.$j.$j.$j;
		$fe["such"][$j] = '';
	}
	$fe["nr"][3] = '9111'; $v = "such"; $fe[$v][0] = '9000'; $fe[$v][2] = '9111'; $fe[$v][4] = '9222'; show3($fe);
	show3(mcgee::equal($fe, "nr", "such", "lfn"));
	*/
	function equal($fe, $haystack, $needles, $id){ 	// needles werden 1:1 im haystack gesucht, geliefert werden haystack-Werte (nämlich die id)
		$h = $haystack; $n = $needles;
		$e = array_intersect($fe[$h], $fe[$n]); $z = -1;
		foreach ($e as $k => $v) {$o[$id][++$z] = $k; $o["found"][$z] = $v;}
		return $o;
	}
	
	function preg($fe, $haystack, $needles, $id) { 	// needles werden mit preg_match im haystack gesucht, geliefert werden haystack-Werte (nämlich die id)
		$f = "found_in"; $fe = data::comp($fe, "@$f@ = '';"); $h = $haystack; $n = $needles;
		for ($j = 0; $j < count($fe[$n]); ++$j){
			$w = $fe[$n][$j];
			for ($k = 0; $k < count($fe[$h]); ++$k){
				if (preg_match("/$w/", $fe[$h][$k]) and trim($w) !== "") $fe[$f][$j] .= " ".$fe[$id][$k];
			}
		}
		return $fe;
	}
	
	function largefile($f, $patt, $o){
		// bis zu 920 TByte    grep -E "ab|cd|ef" largefile.txt > m3.out  
	}
	
	// $fe = data::rnd(150, 2);
	// $fe = data::comp($fe, "@d01@ = @c01@;");
	// $fe = data::comp($fe, "@d02@ = @c02@;");
	// $fe = data::comp($fe, "@d01@ = chr(substr(@c01@, 0, 1) + 65).chr(substr(@c01@, 1, 1) + 65);"); //show3($fe);
	// mcgee::grep($fe, "d01", "/AA|A/");
	function grep($fe, $v, $patt){
		$o = preg_grep($patt, $fe[$v]);
		$t = new table();
		$t->freq(data::arr2fe($o), "value");
	}
	
}


function hoch($v){ return "^".preg_replace("/,/","$,^",$v)."$"; }

//_________ voll funktionierendes Beispiel __________
// $fe = getrnd(10, 5); $fe = fu($fe, "^c[1-2]", "sum2", "summe");
// function colagg
function alt_fu($fe, $v, $fu, $neuvar = "neuvar"){ //z.B. mean über 3 Spalten
	$v = vl($fe[0], $v);
	for($i = 0; $i < count($v); ++$i) $o[] = "@".$v[$i]."@";
	comp($fe, "@$neuvar@ = $fu(array(".implode(",", $o)."));");
	return $fe;
}

//zufallsdaten4(15, 3, $tb);
//fu2("c135mean","stat_mean","^c[1-3]$", $tb);
//php-Funktionen: max, min
function alt_fu2($o, $fu, $v, $tb){  //z.B. Mittelwert über 3 Spalten
	$tb0 = preg_replace("/( where| order).+$/","", $tb);
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";
	$wh  = " where ".fromto($tb," where ","");	
	$v = varlist_ereg4(kontrolliere($v, 1), $tb0).",lfn";			
	newcol($tb0, $o);
	$q = "select $v from $tb";
	$rs = myq($q);
	$gr = mysql_num_fields($rs);
	while($row = mysql_fetch_row($rs)){
		$lfn = $row[$gr - 1];
		array_pop($row);
		eval("\$w = $fu(\$row);");
		myq("update $tb0 set $o = $w $wt lfn=$lfn", 1);
	}
}

function alt_append($fe, $var){
	while (list($r, $zei) = each($fe)) $fe[$r][] = "0";
	$fe[0][count($fe[0]) - 1] = $var;
	return $fe;
}

function alt_appendcol2(&$fe, $var){
	$var = kontrolliere($var);
	$vf = explode(",", $var);
	for ($j = 0; $j < count($vf); ++$j) $fe[0][count($fe[0])] = trim($vf[$j]);
}

function colinsert($fe, $var, $pos){ //function spalteinsert
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	for($i = 0; $i < $gr1; ++$i){
		$s = 0;
		for($j = 0; $j < $gr2; $j++){
			if ($j <  $pos) $o[$i][$j] = $fe[$i][$j];
			if ($j == $pos) {$o[$i][$j] = ""; $o[$i][$j+1] = $fe[$i][$j];}
			if ($j >  $pos) $o[$i][$j+1] = $fe[$i][$j];
		}
	}
	$o[0][$pos] = $var;
	return $o;
}

function rowinsert($fe, $pos){ //function zeileinsert
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	for($i = 0; $i < $gr1; ++$i){
		$r = 0;
		for($j = 0; $j < $gr2; $j++){
			if ($i <  $pos) $o[$i][$j] = $fe[$i][$j];
			if ($i == $pos) {$o[$i][$j] = ""; $o[$i+1][$j] = $fe[$i][$j];}
			if ($i >  $pos) $o[$i+1][$j] = $fe[$i][$j];
		}
	}
	return $o;
}

// echo format2("1.23456","00.00");
// echo format2("0.5","0000.000");
// echo format2("-0.55","0.0");
// echo format2(" -  0.55","0.0"); die;
// echo format2("1129","0.00");
function format2($z, $fo, $tx = "."){
	if (is_numeric($z) == 0 or $z == "") {return $z; }
	$z = preg_replace("/ /", "", $z);
	if (mid($z, 1, 1) == "-") {$z = fromto($z, "-", ""); $mi = "-"; }
	if ($z == "") return;
	$fe = explode(".", $fo);
	$l1 = strlen($fe[0]);
	$l2 = strlen($fe[1]);
	$z = number_format($z, $l2); $z = preg_replace("/,/","", $z);
	$z_ = explode($tx, $z);
	$p1 = $mi.sprintf("%0".$l1."d", $z_[0]);
	$p2 = $z_[1];
	if (instr($fo,".")) return $p1.$tx.$p2; else return $p1;
}

function formatg($w, $gr = 0.2, $fo = "0.000"){
	if (!is_numeric($w)) return $w;
 	if (abs($w) < $gr) return "<font color = grey>".format2($w, $fo)."</font>"; else return format2($w, $fo);
}

//formatiert Zahlen innerhalb von Tags
//echo format3("tg", "<tg id = od0 >11.292345234</tg>","0.00");
function format3($tag, $z, $fo){
	return preg_replace("/(<".$tag."[^>]*>)(.*?)(<\/".$tag.">)/e", "'$1'.format2($2, '$fo')", $z);
}

//formatiert nur Zahlen mit Punkt, d.h. Nachkommawerte, ganze Zahlen bleiben unverändert
function formatif($z, $fo = "0.00"){
	if (instr($z, ".")) return format2($z, $fo); else return $z;
}

//echo replace_betw_tags("<pw>", "</pw>", "aa", $z);
function replace_betw_tags($start, $end, $new, $source) {
	return preg_replace('#('.preg_quote($start).')(.*?)('.preg_quote($end).')#si', '$1'.$new.'$3', $source);
}

//_________________ Beispiele _____________________
//$fe3 = function_on_fe($fe2, "^c[01]", "round(@, 1)");
//$fe3 = function_on_fe($fe2, "^c[01]", "format2(@, '0.0')");
//$fe = function_on_fe($fe, "^a", "preg_replace('/,/', '.', '@')");
function function_on_fe($fe, $vars, $fu){ //apply function, beliebige Funktion
	$v = vl($fe[0], $vars);
	$co = getcols($fe[0], implode(",", $v));
	$cf = explode(",", $co);
	for ($i = 1; $i < count($fe); ++$i) {	
		for ($c = 0; $c < count($cf); ++$c) {
			$w = $fe[$i][$cf[$c]];
			$r = preg_replace("/@/", $w, $fu);
			eval("\$fe[\$i][\$cf[\$c]] = ".$r.";");
		}
	}	
	return $fe;
}

function pwert($p, $fo){
	$p = format2($p, $fo);
	if     (strlen($fo) == 4 and $p == 0) $p = "p < 0.01"  ;
	elseif (strlen($fo) == 5 and $p == 0) $p = "p < 0.001" ;
	elseif (strlen($fo) == 6 and $p == 0) $p = "p < 0.0001";
	else $p = "p = ".$p;
	return $p;
}

function pwertbold($p, $lim = 0.05){
	if ($p <= $lim) return "<b>".$p."</b>"; else return $p;
}

function or_farbe($or){ # www.w3schools.com/colors/colors_picker.asp
	$fa = array('#ffb3b3', '#ff6666', '#ff1a1a', '#cc0000', '#800000', '#4d0000');
	if     ($or >= 1 and $or < 2) return "<p2 style='background-color: ".$fa[0]."' >".$or."</p2>"; 
	if     ($or >= 2 and $or < 3) return "<p2 style='background-color: ".$fa[1]."' >".$or."</p2>"; 
	elseif ($or >= 3 and $or < 4) return "<p2 style='background-color: ".$fa[2]."' >".$or."</p2>";
	elseif ($or >= 4            ) return "<p2 style='background-color: ".$fa[3]."' >".$or."</p2>"; 
	else return $or;
}

//copyvars("v3[1-4]$",$tb,"/^v/","o","varchar(10)");
function alt_copyvars($v, $tb, $repl_old, $repl_by, $ty="real"){
	$v = vl3($v, $tb);
	$fe = explode(",", $v);
	$rfe = preg_replace("$repl_old","$repl_by",$fe);
	for($j = 0; $j < count($fe); $j++){
		comp2("$rfe[$j] = $fe[$j]", $tb, $ty);
	}
}

//copytab($tb, $tb."b");
function copytab($tb, $otb){
	dropt2("^$otb$");
	myq("create table $otb engine = myisam select * from $tb");
}

//_______________ Validierung ____________________
// $fe = getrnd(10, 3);
// for ($j = 1; $j <= 3; ++$j) comp($fe, "@c$j@ = trunc2(@c$j@ / 10);");			
// comp($fe, "@cc@ = @c1@.','.@c2@.','.@c3@;");			
// show(split1($fe, "cc"));

//  splittet auf in abc
//   a  b  c  abc   cc1 cc2 cc3 cc4 cc5 cc6
//   1  2  3  1,2,3   1   1   1
//   4  5  6  4,2,6       1       1       1

function split1($fe, $var, $neu = "cc", $tx = ","){
	comp($fe, "\$a = explode(',', @$var@); @cc_mx@ = max(\$a);");
	$mx = max2(vector($fe, "^cc_mx$"));
	$fe = getmat2($fe, "^(?!(cc_mx)$)");
	for ($j = 0; $j <= $mx; ++$j) comp($fe, "\$a = explode(',', @$var@); if (in_array('$j', \$a)) @".$neu.$j."@ = 1;");
	return $fe;
}

//__________________ Validierung __________________________
// $fe = getrnd(10, 3);
// for ($j = 1; $j <= 3; ++$j) comp($fe, "@c$j@ = trunc2(@c$j@ / 10);");
// comp($fe, "@cc@ = @c1@.','.@c2@.','.@c3@;");
// show(split2($fe, "cc"));

//  splittet abc auf in neu0-2
//     abc neu0 neu1 neu2
//   1,2,3    1    2    3
//   4,5,6    4    5    6

function split2($fe, $v){
	$fe = getrnd(10, 3);
	comp($fe, "@cc@ = @c1@.','.@c2@.','.@c3@;");
	for ($j = 0; $j < 3; ++$j) comp($fe, "\$a = explode(',', @cc@); @neu$j@ = \$a[$j];");
	show($fe);
}

// $fe = getrnd(10, 3, 1, 10);
// $fe = split2b($fe, "^c3$");
// show($fe);
function split2b($fe, $v, $mn, $mx){
	$vf = vl($fe[0], $v); $v = implode(",", $vf);

	if ($mn == "") $mn = min2(vector($fe, $v));
	if ($mx == "") $mx = max2(vector($fe, $v));
		
	for ($j = $mn; $j <= $mx; ++$j) {$fe2 = append($fe, $v."_".$j); $fe = $fe2; }
	
	while (list($r, $zei) = each($fe)) {
		if ($r > 0){
			$fe[$r] = setkeys($fe[0], $zei);
			for ($j = $mn; $j <= $mx; ++$j) if ($fe[$r][$v] == $j) $fe[$r][$v."_".$j] = 1; #else $fe[$r][$v."_".$j] = '';
			$fe[$r] = array_values($fe[$r]);
		}
	}
	return $fe;
}

function split3($fe3, $v){
	$st = uvstufen3($fe3, $v);
	for($j = 0; $j < count($st); ++$j) {
		$n = $v."_".$st[$j]; $w = $st[$j]; 
		$fe3 = comp3($fe3, "if (@$v@  <> '') {if (@$v@  == '$w') @$n@ = 1; else @$n@ = '0'; }");
		
	}
	return $fe3;
}

//round2("^v",$tb,4);
function round2($v,$tb,$decimals=2,$showcmd=0){
	$v = varlist_ereg4(kontrolliere($v, 1),$tb);
	$fe = explode(",",$v);
	for($j=0; $j<count($fe); $j++) comp2("$fe[$j] = round($fe[$j],$decimals) ",$tb,"real",$showcmd);	
}

//pf(preg_match_spec("a1b2c'd3e4f'g5h'i6j76'l8m"));
function preg_match_spec($s) { //entfernt Ziffern nur innerhalb der Hochstriche
	$r = ""; $an = 0;
	for ($i = 0;$i<strlen($s); $i++){
		$c = substr($s,$i,1);
		if ($c == "'"){	$an = ($an==0?1:0);	$r.=$c;	}
		else {if ($an == 0 || ($an == 1 && ( $c < "0" || $c > "9")) ) $r.=$c;}
	}
	return $r;
}

function preg_replace_fe($find, $repl, $s){
	for ($j = 0; $j < count($find); ++$j) $s = preg_replace("/".$find[$j]."/", $repl[$j], $s);
	return $s;
}

//apply("^c","round(\$v,1)",$tb);
function apply($v,$fu,$tb){ //Schleife	
	$v = varlist_ereg4(kontrolliere($v, 1), $tb);	
	$fe = explode(",",$v);	
	for ($i = 0;$i<count($fe); $i++){
		$n = $fe[$i];		
		comp2("$n = ".preg_replace("/\\\$v/",$n,$fu),$tb);
	}
}

function dbase_read2($dbfname,$o) {
	$fdbf = fopen($dbfname,'r');
	$fields = array();
	$buf = fread($fdbf,32);	
	$header=unpack("VRecordCount/vFirstRecord/vRecordLength", substr($buf,4,8));
	$goon = true;
	while ($goon && !feof($fdbf)) {
		$buf = fread($fdbf,32);
		if (substr($buf,0,1)==chr(13)) $goon=false;
		else {
			$field=unpack("a11fieldname/A1fieldtype/Voffset/Cfieldlen/Cfielddec", substr($buf,0,18));
			$fi = fromto(implode(",",$field),"",",");			
			$t = $t.$fi.chr(9);
			$unpackString.="A$field[fieldlen]$field[fieldname]/";
		}
	}
	$t = preg_replace("/".chr(9)."$/","",$t);
	$t = $t.chr(10);
	fseek($fdbf, $header['FirstRecord']+1);
	for ($i=1; $i<=$header['RecordCount']; $i++) {
		$buf = fread($fdbf,$header['RecordLength']);
		$rec=unpack($unpackString,$buf);
		$tx = "@@x@@";
		$r = implode($tx,$rec);
		$r = preg_replace("/\s(.)/","\\1",$r);
		$r = preg_replace("/$tx/",chr(9),$r);
		//pf($i);
		$t = $t.$r.chr(10);
	}
	fclose($fdbf); 
	write2($t,$o);	
	return $t;
} 

function eckig($t){ return "[".preg_replace("/,/","],[",kontrolliere($t))."]"; }

function pf($s = "", $stopatend = 0){
	if (is_array($s)) $s = implode(" ", $s);
	echo "<br> $s <br>";
	if ($stopatend == 1) die;
}

function pf2($s, $stopatend = 0){
	echo $s."\n"; if ($stopatend == 1) die;
}

function erun($s = "", $stopatend = 0){
	global $msg;
	if ($msg == 1) {echo $s."\n"; if ($stopatend == 1) die;}
}

function lastvaluecarriedforward($fe, $v, $addvalue = 0.001){  //LOCF, LVCF, addvalue zeigt die Ersetzung an
	$c = getcols($fe[0], $v);
	$cf = explode(",", $c);
		
	for ($i = 1; $i < count($fe); ++$i){
		for ($j = 1; $j < count($cf); ++$j){
			if ($fe[$i][$cf[$j]] == "") $fe[$i][$cf[$j]] = $fe[$i][$cf[$j - 1]] + $addvalue;
		}
	}
	return $fe;
}

function kill($pfad_und_file_stern_punkt){
	foreach (glob($pfad_und_file_stern_punkt) as $filename) unlink($filename);
}

function read_my_dir($dir, $ext, $tx, &$t){ // read files recursively  //read_my_dir("c:\\commandr\\dat\\", "dbf");
	if (is_dir($dir)){
		if ($dh = opendir($dir)){
			while (($file=readdir($dh)) !== false){
				if (!is_dir($dir."\\".$file) ){
					if (instr($file,".$ext")>0) $t=$t.$dir."\\".$file.$tx;
				} else {
					#if (($file != ".") && ($file != "..")) read_my_dir($dir."\\".$file, $ext, $tx, &$t);
				}
			}
			closedir($dh);
		}
	}	
	return kommaweg($t,$tx);
}

function read_my_dir1($dir, $dirsonly = 1, $tx = ","){
	if (is_dir($dir)){
		if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false){
//*
				if (is_dir($dir."\\".$file) ){
					$t = $t.$dir."\\".$file.$tx;
				} else {
					
					#if (($file != ".") && ($file != "..")) {$t = $t.$dir."\\".$file.$tx; read_my_dir($dir."\\".$file, $ext, $tx, &$t);}
				}
//*/
			}
			closedir($dh);
		}
	}	
	return kommaweg($t,$tx);
}

function read_my_dir2($dir, $tx = ","){
	if (is_dir($dir)){
		if ($dh = opendir($dir)){
			while (($file=readdir($dh)) !== false){
				if (!is_dir($dir."/".$file) ){
					$t = $t.$dir.$file.$tx;
				}
			}
			closedir($dh);
		}
	}	
	return kommaweg($t,$tx);
}

function read_my_dir3($dir, $tx = ","){ //files only
	if (is_dir($dir)){
		if ($dh = opendir($dir)){
			while (($f = readdir($dh)) !== false){
				if (!is_dir($dir."/".$f)) $t[] = $dir."/".$f;
			}
			closedir($dh);
		}
	}
	return $t;
}

function read_my_dir4($dir){  //dirs only
	if (is_dir($dir)){
		if ($dh = opendir($dir)){
			while (($f = readdir($dh)) !== false){
				if (is_dir($dir."/".$f) and $f !== "." and $f !== "..") $t[] = $dir."/".$f;
			}
			closedir($dh);
		}
	}	
	return $t;
}

function dirlist($pa,$sternpunkt){  //echo dirlist("c:\\temp\\temp","*.dat");
	$s = str_replace("*","",$sternpunkt);	
	if ($handle = opendir($pa)) {			
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {			
				if (instr($file,$s)) $t=$t.$file.",";				
			}
		}
		closedir($handle);
	}
	$t = substr($t,0,len($t)-1);
	return $t;
}

function dirlist2($pfad_mit_sternpunkt){ //echo dirlist2("c:\\temp\\temp\\dat");
	foreach (glob($pfad_mit_sternpunkt) as $filename) $t = $t.$filename.",";
	$t = substr($t,0,len($t)-1);
	return $t;
}

function dirlist3($pfad, $sternpunkt){
	foreach (glob($pfad.$sternpunkt) as $filename) {$t = $t.$filename.",";}
	$t = substr($t,0,len($t)-1);
	$t = str_replace($pfad,"",$t);
	return $t;
}

function dirjpg($path = '.'){
	$ignore = array('thumbs', '.', '..');
	$dh = opendir($path);
	while($file = readdir($dh)){
		if(!in_array($file, $ignore)){
			$f = $path."/".$file;
			if(is_dir($f)){
				br(2); echo $file; br(2);
				dirjpg($f);
			} else {
				$f2 = fromto($f, "/eigenes/www", "");
				$hw = "height = '15%' width = auto";
				if (instr($f, "jpg")) echo "<img src = '$f2' $hw >".lz(2);
				if (instr($f, "mp4")) echo "<video src = '$f2' controls $hw></video>".lz(2);
			}
		}
    }
    closedir($dh);
}

// $fe = getDirContents("/eigenes/mydir");
function getDirContents($dir, &$rs = array()){
	$files = scandir($dir);

	foreach($files as $key => $value){
		$p = realpath($dir.DIRECTORY_SEPARATOR.$value);
		if(!is_dir($p)){
			$rs[] = $p;
		} else if($value != "." && $value != ".."){
			getDirContents($p, $rs);
			$rs[] = $p;
		}
	}
	return $rs;
}

class dirjpgsorted extends SplHeap{
	public function __construct(Iterator $iterator){
		foreach ($iterator as $item) $this->insert($item);
	}
	public function compare($b,$a){
		return strcmp($a->getRealpath(), $b->getRealpath());
	}
}

function sl(){return chr(92);}

function delete($file){
	if (file_exists($file)) {
		if (is_dir($file)) {
			$handle = opendir($file); 
			while($filename = readdir($handle)) {
				if ($filename != "." && $filename != "..") delete($file."/".$filename);				
			}
		closedir($handle);
		rmdir($file);
		} else {
			unlink($file);
		}
	}
}

function halt(){ex(); die;}

function kon($s, $anf_end = "") {return kontrolliere($s, $anf_end = ""); }

function kontrolliere($s, $anf_end = ""){
	if (instr($s," to ")==0) return $s;
	$s = str_replace(", ",",",$s);
	$s = str_replace("  "," ",$s);
	$vfe = explode(",",$s);
	for ($j=0;$j<count($vfe);++$j){
		$v = $vfe[$j];
		if (instr($v," to ")) {
			$fe = explode(" to ",$v);
			$n = $fe[0];
			$st="";
			for($i=0;$i<len($n);++$i){
				$b = substr($n,$i,1);
				if (!is_numeric($b)) $st = $st.$b;
			}
			$v = str_replace($st,"",$v);
			$fe = explode(" to ",$v);
			$lo = $fe[0];
			$hi = $fe[1];
			$lv="";
			for($i=$lo;$i<=$hi;++$i){
				$lv = $lv.$st.$i;
				if ($i<$hi) $lv=$lv.",";
			}
			$vfe[$j]=$lv;
		}
	}
	$t = implode(",",$vfe);
	if ($anf_end==1) $t = "^".preg_replace("/,/","\$,^",$t)."$";
	return $t;
}

function timediff($out, $date_begin, $date_end, $y_m_w_d_h){
    $f = $y_m_w_d_h;
    if ($f=="y") $s = 365.25;
    if ($f=="m") $s = 30;
    if ($f=="w") $s = 7;
    if ($f=="d") $s = 1;
    if ($f=="h") $s = 1 / 24;
    $sy = "comp $out = ($date_end - $date_begin)/(60*60*24*$s).";
    ex($sy);
}

function var_da($fe, $v){
	for ($j = 0; $j < count($fe[0]); ++$j) if (preg_match("/$v/", $fe[0][$j])) return true;
	return false;
}

// $fe1  = getrnd(5, 1); $fe1 = comp($fe1, "@id@ = 1;");
// $fe2  = getrnd(5, 1); $fe2 = comp($fe2, "@id@ = 2;"); 
// $fe12 = addarr2($fe1,$fe2); show($fe12);
// $fe12 = casestovars($fe12, "id", "lfn", "c1"); show($fe12);
#$fe = casestovars($fe, "^gr$|^id$", "zeitindex", "v1");
function alt_casestovars($fe, $id, $index, $av){
	$fe = getmat2($fe, "$id|$index|$av");
	$st = explode(",", uvstufen($fe, $index));
	#$fe0 = agg($fe, $id, "min2");
	for($j = 0; $j < count($st); ++$j) {
		$se = selif2($fe, "^$index$", "^$st[$j]$");
		$se[0] = preg_replace("/($av)/", "\$1_".$st[$j], $se[0]);
		#show($se);
		if ($j == 0) $o = $se;
		if ($j >  0) $o = mergemulti_with_keys($o , $se, $id);
		$o = getmat2($o, "^(?!.*(_dopp_).*$|^$index$)");
		#show($o);
	}
	return $o;
}

//show(getrnd(10, 5));
function getrnd($r, $c, $lo = 0, $up = 100){
	for($j = 1; $j <= $c; ++$j) $fe[0][$j - 1] = "c".$j;
	for($i = 1; $i <= $r; ++$i) for($j = 0; $j < $c; ++$j) $fe[$i][$j] = mt_rand($lo, $up);
	$fe = lfn($fe);
	return $fe;
}

function zufallsstring($lae){return substr(md5(rand()), 0, $lae); } //zufallszahl

function runde($var, $runde_format){
    $f = $runde_format;
    $v = $var;
    $ex("if sysmis($v)=0 $v = number(string($v,$f),f14). %b formats $v ($f).");
}

function ageadjusted_prevalence($group,$event){ //ageadjusted_prevalence("agekat","event");
	//Idee: Summenprodukt aus Raten pro Stratum multipliziert mit Anteil des Stratums in der Population
	//ex("data list fixed /agekat (f2) event (f1). %b begin data. %b 1 0 %b 1 1 %b 1 0 %b 1 1 %b 1 1 %b 2 1 %b 2 1 %b 2 1 %b 2 1 %b 2 1 %b 3 0 %b 3 1 %b 3 0 %b 3 0 %b 3 0 %b end data."); ageadjusted_prevalence("agekat","event");
	ex("comp alle=1.");
	$p = "c:\\temp\\temp\\";
	ex("aggreg /out '".$p."prev.sav' /break $group /prev = mean($event).");
	ex("aggreg /out '".$p."alle.sav' /break alle /tot = sum(alle).");
	ex("aggreg /out '".$p."$group.sav' /break $group /tot_sub = sum(alle).");

	get($p,"$group.sav");
	ex("comp alle=1.");
	ex("match files /file * /table '".$p."alle.sav' /by alle.");
	ex("comp gew = tot_sub / tot.");

	ex("match files /file * /table '".$p."prev.sav' /by $group.");
	ex("comp gewprev = gew * prev.");
	ex("aggreg /out * /break alle /gewprev = sum(gewprev).");
}

function alt_renamevars($fe, $what, $by){
	$fe[0] = preg_replace("/$what/", $by, $fe[0]);
	return $fe;
}

function round_($invar,$outvar,$precision){ //round_("v13","v13k","f14.3");
	ex("comp $outvar = number(string($invar,$precision),f18).");
}

function write($outtext, $outfile="/tmp/outfile.txt"){ //write("aaa");
	fwrite(fopen($outfile,"w+"),$outtext);
}

//function mytab
function descgrouped($av, $uv, $tb_oder_fe){
	if (!is_array($tb_oder_fe)) {
		$tb = $tb_oder_fe;
		$uv = vl4($uv, $tb);
		$av = vl4($av, $tb);
		$fe = getmat("select $av, $uv from ".$tb);
	} else {
		$fe = $tb_oder_fe;
		$uv = getvars($fe[0], $uv); $uv = doppelte_weg($uv);
		$av = getvars($fe[0], $av);
		$fe = getmat2($fe, $av.",".$uv);
	}	
	
	$st = uvstufen($fe, $uv); $stf = explode(",", $st); $stz = count($stf);
	$avf = explode(",", $av); $avz = count($avf);
	
	$fe4[0] = array("", givelabel($uv), "mean", "sd", "min", "median", "max", "n");
	for ($j = 0; $j < $avz; ++$j){
		$a = $avf[$j];		
		for($i = 0; $i < count($stf); ++$i){
			$fe2 = selif2($fe, "^$uv$", $stf[$i]);
			$ve = vector($fe2, "^$a$");
			unset($fe3);
			if ($i == 0) $fe3[0] = givelabel($a); else $fe3[0] = "";
			$fe3[1] = givelabel($uv, $stf[$i]);
			$fe3[2] = mean2($ve);
			$fe3[3] = sd2($ve);
			$fe3[4] = min2($ve);
			$fe3[5] = median2($ve);
			$fe3[6] = max2($ve);
			$fe3[7] = count2($ve);
		
			$fe4[] = $fe3;
		}
	}
	$fe4 = function_on_fe($fe4, "^[ms]", "format2(@, '0.0')");
	show($fe4);
}

// n-Zahl ermitteln  count2($fe["lfn"])
function count2($d) {
	if (!is_array($d)) $fe = explode(",",$d); else $fe = $d;	
	#for ($j = 0; $j < count($fe); ++$j) $fe[$j] = trim($fe[$j]);
	$fe = array_filter($fe, "is_numeric");
	return count($fe);
}

function countnum2($d) {
	if (!is_array($d)) $fe = explode(",",$d); else $fe = $d;
	$z = 0;
	for ($j = 0; $j < count($fe); ++$j) if (is_numeric($fe[$j])) ++$z;
	return $z;
}

//comp($fe, "@mean@ = mean2(array(@c1@,@c2@,@c3@));");
//$c = vl3s($fe, "^c"); $fe = comp3($fe, "if (count2(array($c)) == 4) @score@ = sum2(array($c));");
function mean2($d) {
	if (!is_array($d)) $fe = explode(",",$d); else $fe = $d;
	$fe = array_filter($fe, "is_numeric");
	if (count($fe) > 0) return (array_sum($fe) / count($fe));
}

function pm2($d) {
	if (!is_array($d)) $fe = explode(",",$d); else $fe = $d;
	$fe = array_filter($fe, "is_numeric");
	if (count($fe) > 0) return round(array_sum($fe) / count($fe) * 100, 1);
}

//1. comp($fe, "@median@ = median2(array(@c1@,@c2@,@c3@));");
//2. $v = "^c[1-3]$"; comp($fe, "@median@ = median2(array(@".implode("@,@", vl($fe[0], $v))."@));");
function median2($d){ //echo "md=".median2("1,2,3");
	if (!is_array($d)) $fe = explode(",",$d); else $fe = $d;
	$fe = array_filter($fe, "is_numeric");
	sort($fe);
	$gr = count($fe);
	if (($gr % 2) == 0) {
		$i = $gr / 2;
		return (($fe[$i - 1] + $fe[$i]) / 2) + 0;
	} else {
		$i = ($gr - 1) / 2;
		return $fe[$i] + 0;
	}
}

//1. comp($fe, "@su@ = sum2(array(@v1@,@v2@,@v3@));");
//2. $v = "^c[1-3]$"; comp($fe, "@sum@ = sum2(array(@".implode("@,@", vl($fe[0], $v))."@));");
//3. $fe = comp3($fe, "@score@ = sum2(array(".vl3s($fe, "^v")."));");
//4. $c = vl3s($fe, "c"); $fe = comp3($fe, "if (count2(array($c)) == 4) @score@ = sum2(array($c));");
function sum2($d) {  //echo "s=".sum2("1,2,,,1,2");
	if (!is_array($d)) $fe = explode(",",$d); else $fe = $d;
	$fe = array_filter($fe, "is_numeric");	
	if (count($fe) > 0) return array_sum($fe);
}

function sum3($fe){
	return array_sum($fe);
}

function sd2($d) {
	if (!is_array($d)) $fe = explode(",",$d); else $fe = $d;	
	$fe = array_filter($fe, "is_numeric");
	$n = count($fe);
	$mean = mean2($d);
	$sum = 0;
	foreach ($d as $element) {
		$sum += pow (($element - $mean), 2);
	}
	return sqrt (($sum / ($n - 1)));
}

function se2($d) {return sd2($d) / sqrt(count2($d)); }

function min2($d) {
	if (!is_array($d)) $fe = explode(",",$d); else $fe = $d;	
	$fe = array_filter($fe, "is_numeric");
	if (count($fe) > 0) return min($fe) + 0;
}

function max2($d) {
	if (!is_array($d)) $fe = explode(",",$d); else $fe = $d;	
	$fe = array_filter($fe, "is_numeric");
	if (count($fe) > 0) return max($fe) + 0;
}

function is_even($nr){if ($nr % 2 == 0) return 1; else return 0; }

// $fe = getrnd(200,1); $fe = walk($fe, "w_copy", "c1b", array("c1")); $fe = walk($fe, "w_reco2", "c1b", array("0 - 50 = 0", "51 - 100 = 1"));
// freq($fe, "c1b");
// desc($fe, "c1b", "pm2,ci_l,ci_u,sum2,count2");
function ci_l($d){ 							// lower CI nach Sachs S.436
	if (!is_array($d)) $fe = explode(",",$d); else $fe = $d;
	$fe = array_filter($fe, "is_numeric");
	if (count($fe) > 0) {
		$n = count2($fe);
		$p = sum2($fe) / $n;
		$a = 0.95 + (1 - 0.95) / 2;
		$z = norminv($a);
		$l = round(($p + 1 / (2 * $n) - $z * sqrt($p * (1 - $p) / $n)) * 100, 1);
		if ($l < 0) $l = 0;
		return $l;
	}		
}

function ci_u($d){ 							// upper CI nach Sachs S.436
	if (!is_array($d)) $fe = explode(",",$d); else $fe = $d;	
	$fe = array_filter($fe, "is_numeric");
	if (count($fe) > 0) {
		$n = count2($fe);
		$p = sum2($fe) / $n;
		$a = 0.95 + (1 - 0.95) / 2;
		$z = norminv($a);
		$u = round(($p - 1 / (2 * $n) + $z * sqrt($p * (1 - $p) / $n)) * 100, 1);
		if ($u > 100) $u = 100;
		return $u;
	}
}

function mid($s,$von,$lae){return substr($s,$von-1,$lae);}
function right($str){return substr($str,len($str)-1,1);}
function len($str){return strlen($str);}
function br($zahl){for($i=1; $i<=$zahl; $i++) {echo "<br>";}}
function br2($zahl){for($i=1; $i<=$zahl; $i++) {return "<br>";}}

function liste($von,$bis){
    $a[]="";
    if ($von <=$bis) for ($j=$von; $j<=$bis; $j++) $a[] = $j;
    if ($von > $bis) for ($j=$von; $j>=$bis; $j--) $a[] = $j;
    return $a;
    }

function liste2($s, $trennz){
    if (instr($s,"-")==false) return $s;
    $fe = explode("-",$s);
    for($i=$fe[0]; $i<=$fe[1]; $i++) {
        $a = $a.$i;
        if ($i<$fe[1]) $a = $a.$trennz;
    }
    return $a;
}

function liste3($li, $trennz){
    $fe1 = explode(",",$li);
    for ($i=0; $i<count($fe1); $i++){
        if (instr($fe1[$i],"-")){$fe1[$i]=liste2($fe1[$i],",");}
    }
    $li = implode(",",$fe1);
    $fe2 = explode(",",$li);
    return $fe2;
}

function kette($sql_with_one_field, $tx=","){
	$rs = myq($sql_with_one_field);
	while($row = mysqli_fetch_row($rs)) $t[] = $row[0];
	if (count($t) > 0) return implode($tx, $t);
}

function kette2($q, $tx = ",", $umbr = "|", $varnames = 1){
	$rs = myq($q);
	$gr = mysql_num_fields($rs);
	$r = mysql_num_rows($rs);
	$zz = 0;
	while($row = mysql_fetch_row($rs)){
		$z++;
		if ($varnames == 1 and $z == 1) {
			for ($i = 0; $i < $gr; $i++) {
				$t .= mysql_field_name($rs,$i);
				if ($i < $gr - 1) $t .= $tx; 
			}
			$t .= $umbr;
		}
    	for ($i = 0; $i < $gr; $i++) {             
		$t .= $row[$i];
		if ($i < $gr - 1) $t .= $tx; 
        }
        if ($z < $r) $t .= $umbr;
    }
    return $t;
}
    
function mystr($s,$boden){
    if ($boden==0) $a = ucwords(str_replace('_',' ',$s));
    if ($boden==1) $a = strtolower(str_replace(' ','_',$s));
    return $a;
    }

function alt_indexda($tab, $name){
    $rs = mysql_query("show index from $tab");
    echo mysql_error();
    while ($row = mysql_fetch_row($rs)){
        $i = $row[2];
        if (strtolower($i)==strtolower($name)) return true;
        }
    return false;
}

function colexists($tab,$colname){
    $rs = mysql_query("show columns from $tab");
    while ($row = mysql_fetch_row($rs)){
        $i = $row[0];
        if (strtolower($i)==strtolower($colname)) return true;
        }
    return false;
}

function str_or_null($x){
    if (trim($x)=="") return "null"; else return "'$x'";
}

function num_or_null($x){
    if (is_numeric($x)) return $x; else return "null";
}

function recwert($s){
    $rs = myq($s);
    $row = mysqli_fetch_row($rs); err();
    return $row[0];
}

function recwert2($s){$fe = get($s); return $fe[1][0]; }

function recwert3($s){
    $rs = myq($s);
    $row = mysql_fetch_row($rs); err();
    return $row;
}

//type("v22", $tb);
function type($v, $tb){ //Tabelle mit Spaltentyen (text, double)
	$db = currdb();
	$v = vl4($v, $tb); $v = "^".preg_replace('/,/', '$|^', $v)."$";
	$fe = get("select column_name as col, data_type as type from information_schema.columns where table_schema = '$db' and table_name = '$tb' and preg_position('/$v/', column_name)");
	comp($fe, "@label@ = givelabel(@col@);");
	show($fe);
}

function typef($v, $tb){
	$db = currdb(); $v = vl4($v, $tb);
	return recwert("select data_type from information_schema.columns where table_schema = '$db' and table_name = '$tb' and preg_position('/$v/', column_name)");
}

function givelabel($var, $code = "", $giveset = 0){
	myq("create table if not exists labels (var text, code text, codelabel text, varlabel text, varset text, lfn real) engine = myisam");
	if ($code == "") {
		$r = recwert("select varlabel from labels where var = '$var' limit 1 ");
		if ($r == "") $r = $var;
		if ($r == "(leer)") $r = "";
	}
	if ($code."" <> "") {
		if ($code == 999) return "Gesamt";
		$r = recwert("select codelabel from labels where var = '$var' and code = '$code' limit 1 ");
		if ($r == "") $r = $code;
		if ($r == "(leer)") $r = "";
	}
	if ($giveset == 1) {
		$v = explode(",", $var);
		$r = recwert("select varset from labels where var = '".$v[0]."' limit 1 ");
		if ($r == "") $r = $var;
		if ($r == "(leer)") $r = "";
	}
	$r = preg_replace("/_@k@_/", ",", $r);
	$r = preg_replace("/_@h@_/", "'", $r);
	$r = preg_replace("/\^/", " ", $r);
	$r = label::um($r);
	return trim($r);
}

function give_art($var){ //artikel
	$r = recwert("select gender from labels where var = '$var' limit 1 ");
	if ($r == 'fs') return "die";
	if ($r == 'fp') return "die";
	if ($r == 'ms') return "der";
	if ($r == 'mp') return "die";
	if ($r == 'ns') return "das";
	if ($r == 'np') return "die";
	
}

function give_sp($var, $what){ //singular, plural
	$g = recwert("select gender from labels where var = '$var' limit 1 ");
	if (instr($g, 's')) return fromto($what, "", "/");
	if (instr($g, 'p')) return fromto($what, "/", "");
}

function give_fm($var, $what){ //female, male
	$g = recwert("select gender from labels where var = '$var' limit 1 ");
	if (instr($g, 'f')) return fromto($what, "", "/");
	if (instr($g, 'm')) return fromto($what, "/", "");
}

//givelabel_comb($uv, $tb);
function givelabel_comb($uv, $tb, $tx = " - "){
	global $labels;
	$uv = vl2($uv, $tb);
	$uvf = explode(",", $uv);
	concat2(hoch($uv), $tb, ";", "tmp_");
	$st = uvstufen2("tmp_", $tb, " | ");
	$stf = explode(" | ", $st);
	for ($k = 0; $k < count($stf); ++$k) {
		$cf = explode(";", $stf[$k]);		
		unset($t1);
		for ($j = 0; $j < count($uvf); ++$j) $t1[] = givelabel($uvf[$j],$cf[$j],0);
		$t2[] = implode($tx,$t1);
	}
	return "'".implode("','", $t2)."'";
}

function givelabel_list($v, $tb, $gaense = 0){
	$v = vl2($v, $tb);
	$vfe = explode(",", $v);	
	for ($k = 0; $k < count($vfe); ++$k) {
		$r = recwert("select varlabel from labels where var = '$vfe[$k]' limit 1 ");
		//$r = preg_replace("/_@k@_/", ",", $r);
		if ($r == "") $r = $vfe[$k];
		$t[] = $r;
	}
	if ($gaense == 1) return "'".implode("','", $t)."'"; else return implode(", ", $t);
}

function givelabel_list2($v, $tx = ","){
	$vfe = explode($tx, $v);
	for ($k = 0; $k < count($vfe); ++$k) {
		$r = recwert("select varlabel from labels where var = '$vfe[$k]' limit 1 ");
		if ($r == "") $r = $vfe[$k];
		$t[] = $r;
	}
	return $t;
}

function givelabel_list3($t, $tx = " "){
	$vfe = explode($tx, " ".$t);
	for ($k = 0; $k < count($vfe); ++$k) {
		$v = trim($vfe[$k]);
		if (!is_numeric($v) and $v !== "p") {
			if (instr($v,":") == 0) $vfe[$k] = givelabel($v);
			else {
				$kf = explode(":", $v);
				$kf[0] = givelabel(trim($kf[0]));
				$kf[1] = givelabel(trim($kf[1]));
				$vfe[$k] = implode(" by ", $kf);
			}
		}
	}
	return implode($tx, $vfe);
}

function givelabel_codes($uv, $tb, $gaense = 1){
	$st = uvstufen2($uv, $tb);
	$stf = explode(",", $st);
	for ($j = 0; $j < count($stf); ++$j) {
		$o[] = givelabel($uv, $stf[$j]);
	}
	if ($gaense == 1) return "'".implode("','", $o)."'"; else return implode(",", $o);
}

function givelabel_codes2($fe, $uv, $gaense = 1){
	$st = uvstufen($fe, $uv);
	$stf = explode(",", $st);
	for ($j = 0; $j < count($stf); ++$j) $o[] = givelabel($uv, $stf[$j]);
	if ($gaense == 1) return "'".implode("','", $o)."'"; else return implode(",", $o);
}

//echo givelabeltg("tg", "<tg id = la0 >tki_eq_m3</tg>");
function givelabeltg($tag, $l){
	return preg_replace("/(<".$tag."[^>]*>)(.*?)(<\/".$tag.">)/e", "'$1'.givelabel('$2').'$3'", $l);
}

function labelheaders($fe){ //labeln
	for ($j = 0; $j < count($fe[0]); ++$j) $fe[0][$j] = givelabel($fe[0][$j]);
	return $fe;
}

function labelheaders3($fe){
	foreach ($fe as $key => $value) $fe2[label::v($key)] = $value;
	return $fe2;
}

function label_list($arr){
	for ($j = 0; $j < count($arr); ++$j) $arr[$j] = givelabel($arr[$j]);
	return $arr;
}

function labelcolumn0($fe){
	for ($i = 1; $i < count($fe); ++$i) $fe[$i][0] = givelabel($fe[$i][0]);
	return $fe;
}

function labelcolumn($fe, $c, $uv){
	for ($i = 1; $i < count($fe); ++$i) $fe[$i][$c] = givelabel($uv, $fe[$i][$c] + 0);
	return $fe;
}

function whand($s,$wh){
    $s = strtolower($s);
    if (strpos($s," where")>=0 and $wh<>"") return $s." and ".$wh; else return $s." where ".$wh;
}

function wt($q){
    if (instr($q," where ")) return " and "; else return " where ";
}

function instr($string, $was){
    $string = strtolower($string);
    $was = strtolower($was);
    if ($string <> "" and $was <> ""){
        if (strpos("@@@@@".$string,$was)>0) return true;
    }
    else return false;
}

function auskl($s){
    $kl1 = strpos($s, "(");
    $kl2 = strpos($s, ")");
    return substr($s, $kl1 + 1, $kl2 - $kl1 - 1);
}

# 1. echo auskl2("abc (aaa) def (bbb) asdf");
# 2. echo preg_replace('/^.*\((.*)\).*$/', '$1', "abc (abc) def (hijk) abc");
# 3. echo preg_replace('/^.*\((.*)\).*$/', '$1', "abc (def) def");
//4. all strings with brackets 
//	preg_match_all("/\([^\)]*\)/", '(aa) is a (bb) string, (cc) my (dd).', $matches);
//	echo implode("", $matches[0]);
//5. echo auskl2("abc (nur) abc (der Text) abc (in) abc (Klammern)");
function auskl2($s){
	preg_match_all("/\([^\)]*\)/", $s, $matches);
	$o = implode(" ", $matches[0]);
	return preg_replace("/\(|\)/", "", $o);
}

#echo ohnekl("ABC is outside and (Test1(even deeper) yes (this (works) too)) DEF is outside, too (((ins)id)e)");
function ohnekl($s){
	return preg_replace("/\(([^()]*+|(?R))*\)/","", $s); 
}

//function mult, sucht in einer oder in mehreren Spalten, ersetzt Labels durch 1000er Nummern 
//und erstellt eine Labelstabelle
//code_labels("^v[1-5]$", $tb, $tb."_labels1");
function code_labels($v, $tb, $otb){
	$v0 = $v;
	$v = varlist_ereg4(kontrolliere($v, 1), $tb);	
	$fe = explode(",", $v);
	$t = $tb."_tmp";
	myq("drop table if exists $t"); myq("create table $t (l text) type myisam");
	for ($i = 0; $i < count($fe); ++$i){
		$n = $fe[$i];
		myq("insert into $t select $n from $tb group by $n", 0);
	}
	myq("drop table if exists $otb");
	myq("create table if not exists $otb (l text, n int, c int) type myisam");
	$rs = mysql_query("select l, count(l) from $t where l <> '' group by l");	
	$z = 1000;	
	while($row = mysql_fetch_row($rs)){
		$r0 = $row[0]; $r1 = $row[1];	
		$a = recwert("select l from $otb where l = '$r0' limit 1");
		if ($a == "") {
			++$z;
			myq("insert into $otb (l, n, c) values ('$r0', $r1, $z)"); 
			recode3($v0, $tb, array("'$r0'=$z"));
		}
	}
	myq("drop table $t");	
}

function fromto($s,$beg,$end){
	if (instr($s, $beg) == false) {$b = 0;} else {$b = strpos($s,$beg) + strlen($beg); $s = substr($s,$b); $b = 0;}
	if (instr($s, $end) == false) $e = strlen($s); else $e = strpos($s,$end);
	return substr($s, $b, $e);
}

// echo ft("abc.a1.a1.2c3", "1\.", 0);
// echo preg_split("/1\./", "abc.a1.a1.2c3")[0];
// $s = preg_replace("/(.*?):(.*)/", "$1", "abc:abc:abc");  #delete after ':'
function ft($s, $tx, $el = "0"){
	$fe = preg_split("/$tx/", $s);
	if ($el == "") $el = "0";
	if ($el == "last") $el = count($fe) - 1;
	return $fe[$el];
}

function fromto2($s,$beg,$end){
    $s = substr($s,strpos($s,$beg)+strlen($beg));
    $s = substr($s,0,strpos($s,$end));
    return $s;
}

function fromtolast($s,$beg,$end){
    while (instr($s,$beg)) {
        $s = substr($s,strpos($s,$beg)+strlen($beg));
    }
    if ($end=="") return $s; else return fromto($s,"",$end);
}

function lastfile($p){
	foreach (glob("$p/*", GLOB_BRACE) as $fi) if (filemtime($fi) > $n) {$n = filemtime($fi); $l = $fi;}
	return $l; 
}

function null_or_dat($wert){
    if ($wert<>"") return date("Y-m-d H:i:s"); else return " null ";
}

//$time_start = microtime(true);
//echop("time elapsed: ".format2(microtime(true) - $time_start, "0.000")." s");
function getmicrotime(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

// echo "infe = ".infe(array(1,2,3,4), 1);
function infe($fe, $wert){
	for ($j = 0; $j < count($fe); ++$j) if (trim($fe[$j]) == trim($wert)) return true;
	return false;
}

function inlist($fe, $what){
    $l = 0;
    for ($i = 0; $i < count($fe); ++$i){
        if (trim(strtolower($fe[$i]))==trim(strtolower($what))) $l = 1;
    }
    return $l;
}

function inlist2($fe, $what){
    for ($i = 0;$i < count($fe);++$i){if (trim($fe[$i]) == trim($what)) return true; }
    return false;
}

//$l1 = "a,b";
//$l2 = "a,b,c,d";
//echo (inlist3($l1, $l2)); 
function inlist3($l1, $l2){  //vergleiche zwei Listen $string1 und $string2, eliminiere aus der zweiten Liste, was schon in der ersten ist
	$l1 = explode(",", $l1);
	$l2 = explode(",", $l2);
	for ($i = 0; $i < count($l1); ++$i){
		for ($j = 0; $j < count($l2); ++$j){
			if ($l1[$i] == $l2[$j]) $l2[$j] = "";
		}
	}
	for ($j = 0; $j < count($l2); ++$j){
		if ($l2[$j] <> "") $fe[] = $l2[$j];
	}	
	if (count($fe) > 0) $l2 = implode(",", $fe);	
	return $l2;
}

function inlist3b($li, $wert){
	for ($j = 0; $j < count($li); ++$j) if (trim($li[$j]) == trim($wert)) return 1;
	return 0;
}

function inlist4($wert, $li){
	for ($j = 0; $j < count($li); ++$j) if ($li[$j] == $wert) return true;
	return false;
}

function prefix($s, $pre){
	$fe = explode(",", $s);
	for ($i = 0; $i < count($fe); ++$i) $fe[$i] = $pre.$fe[$i];
	return implode(",",$fe);
}

function elements_in_str($s,$fe){
    for ($i = 0; $i < count($fe); ++$i){
        if (instr($s,$fe[$i])>0) return true;
    }
    return false;
}

function rstoarray($rs){
	while($row = mysql_fetch_row($rs)) $fe[] = $row[0];
	return $fe;
}

function prozent($v,$t){
    $mi = recwert("select count(1) from $t ".wt($t)." $v is null");
    $su = recwert("select count($v) from $t");

    $q = "select $v, count($v) as Zahl,concat(round(count($v)/$su*100,0),'%') as prozent
          from $t".wt($t)."isnull($v)=0 group by $v";
    machetab($q,1,"","");
    echo "<center>total: n=$su, fehlend: n=$mi</center>";
}

function gaensefuesseweg($t){
         if (substr($t,0,1)==chr(34)) $t = substr($t,1);
         $l = strlen($t);
         if (substr($t,$l-1,1)==chr(34)) $t = substr($t,0,$l-1);
         return $t;
}

function suffix($vars, $suffix){
	$v = $vars;
	$fe = explode(",", kontrolliere($v, ","));
	$gr = count($fe);
	for($i=0;$i<$gr;++$i){
		$t = $t.$fe[$i].$suffix;
		if ($i < $gr) $t = $t.", ";
	}
	return $t;
}

function suffix2($vars, $suffix){
	$v = $vars;
	$fe = explode(",", kontrolliere($v, ","));
	return implode($suffix.",",$fe).$suffix;
}

function prae_suff($v, $prae, $suff){
	$fe = explode(",", $v);
	$gr = count($fe);
	for($i = 0; $i < $gr; ++$i) $fe[$i] = $prae.$fe[$i].$suff;
	return implode(",", $fe);
}

function prae($ar, $prae){
	for($i = 0; $i < count($ar); ++$i) $ar[$i] = $prae.$ar[$i];
	return $ar;
}

function kommaweg($t,$tx=","){
	if (substr($t,strlen($t)-1,1)==$tx) return substr($t,0,strlen($t)-1); else return $t;
}

//echo implode(",",seq("1-5,11-15"));
function seq($s){
	$s = kontrolliere(str_replace("-", " to ", $s));
	return explode(",", $s);
}

//show($fe, "1,2"); //1. Spalte li, ab 2. Spalte zentriert, 1 = li, 2 = center, 3 = re
//show($fe, "1");   //links für die 1. Spalte wird fortgesetzt
//show($fe, "1,3", "100,50", "0,1,5");   //links für die 1. Spalte, dann ab 2. Spalte rechts wird fortgesetzt, erste Spalte 100 dann ab 2. immer 50 breit, Colspan in Zeile 0, Spalte 1, Span über 5
function show($fe, $c = "3", $w = "", $colspan = "", $row0insert = ""){
	$c = explode(",", $c); $al = array("ll", "cc", "rr");
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	$l = chr(10).chr(9).chr(9);
	$wf = explode(",", $w);
	$cs = explode(",", $colspan);
	$na = "tb_".zufallsstring(5);
	$tabname = "id = ".$na; registertab($na);
	$t = "<table $tabname border = 1 style = 'background-color:#E6E6E6;'>".$l;
	$t .= $row0insert;
	for ($j = 0; $j < $gr2; ++$j) {$w = $wf[$j]; if ($wf[$j] == "") $w = $wf[count($wf) - 1]; $t .= "<col width = '".$w."px'>".$l; }
	for ($i = 0; $i < $gr1; ++$i) {
		$t .= "<tr>";
			for ($j = 0; $j < $gr2; ++$j) {
			
				$cl = $al[$c[$j] - 1];
				if ($cl == "") $cl = $al[$c[count($c) - 1] - 1];
				if ($cl == "") $cl = "rr";
				
				if ($i == $cs[0] and $j == $cs[1]) {$csp = "colspan = ".$cs[2]; $cl = "cc";} else $csp = "";
				$t .= "<td $csp class = $cl >".$fe[$i][$j]."</td>";  
				if ($i == $cs[0] and $j == $cs[1]) $j = $j + $cs[2];
				
			}
		$t .= "</tr>".$l;
	}
	$t .= "</table>".$l;
	echop($t); return $t;
}

//mit foreach, wenn kein lauf. Index da ist
function show2($fe){
	$t = "<table border = 1 style='background-color:#E6E6E6;'>";
	reset($fe); while (list($k, $zei) = each($fe)) $t .= "<tr><td class = s_or>".implode("</td><td class = s_or>", $zei)."</td><tr>";
	#foreach ($fe as $k => $v) $t .= "<tr><td class = s_or>".implode("</td><td class = s_or>", $v)."</td><tr>";
	$t .= "</table>";
	echop($t);
}

// Reihenfolge der Rähmchen wichtig!
// showneu($o3, "1,1,3", "100,75,50", "^0$|[48]:.:s_u,^0$:.:s_o,^[1-9]$|^[1-9][0-9]$:[1-5]$:d_o,.:[35]:s_r", $t);
function showneu($fe, $align = "1,2", $widths = "350,75", $borders = "^0$:.:s_o s_u1,^last$:.:s_u1,[1-9]|1[0]:.:d_o", $row0insert = "", $savename = ""){
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	$l = chr(10);
	$rf = explode(",", $borders);
	$wf = explode(",", $widths);
	$al = explode(",", $align); $alf = array("", "ll", "cc", "rr"); for ($j = 0; $j < $gr2; ++$j) {$al[$j] = $alf[$al[$j]]; if ($al[$j] == "") $al[$j] = $al[$j - 1];}
	
	for ($i = 0; $i < count($fe); ++$i) {
		for ($j = 0; $j < count($fe[0]); ++$j) {
			unset($clf);
			for ($k = 0; $k < count($rf); ++$k) {
				$r1 = explode(":", $rf[$k]);
				$r1[0] = preg_replace("/last/"    , $gr1 - 1, $r1[0]);
				$r1[0] = preg_replace("/lastbut1/", $gr1 - 2, $r1[0]);
				$r1[1] = preg_replace("/last/"    , $gr2 - 1, $r1[1]);
				$r1[1] = preg_replace("/lastbut1/", $gr2 - 2, $r1[1]);
			
				$mi = preg_match("/".$r1[0]."/", $i);
				$mj = preg_match("/".$r1[1]."/", $j);
				if ($mi == 1 and $mj == 1) $clf[] = $r1[2];
			}						
			$cl = implode(" ", array_unique($clf));
			if ($st[$i][$j] == "") $st[$i][$j] = $cl;
		}
	}
	
	$na = "tb_".zufallsstring(5);
	$tabname = "id = ".$na;
	$t = $l.$l."<table $tabname border = 0 style = 'background-color:white' >".$l; # #E6E6E6
	$t .= $row0insert;
	for ($j = 0; $j < $gr2; ++$j) {$w = $wf[$j]; if ($wf[$j] == "") $w = $wf[count($wf) - 1]; $t .= "<col width = '".$w."px'>".$l; }
	for ($i = 0; $i < count($fe); ++$i) {
		$t .= "<tr id = zei$i>";
		for ($j = 0; $j < $gr2; ++$j) {
			$lb = $fe[0][$j]; if ($lb == "") $lb = "col".$j;
			#if ($i == 0) $ro = "rotate"; else $ro = "";
			if ($st[$i][$j] !== "") $cl = "class = '$ro ".$al[$j]." ".$st[$i][$j]."'"; else $cl = "";
			$t .= "<td $cl id = '".$lb.$i."' >".$fe[$i][$j]."</td>";
		}
		$t .= "</tr>".$l;
	}
	$t .= "</table>".$l.$l;
	echop($t);
	if ($savename !== "") {write::fe($fe, $savename); write2($t, $savename.".html");}
}

// $v = "bin1,bin2,bin3";
// $fe = getmat3("select $v from config where lfn < 200");
// desc3($fe, $v, "pm2,sum2,count2");
// $fe = configtable("config", $v);
function configtable($tb, $v, $topfilter = 15){
	$vf = explode(",", $v);
	$v2 = preg_replace("/,/", ",',',", $v);
	$fe = getmat3("select * from (select concat($v2) as config, count(concat($v)) as count2 from $tb group by config order by config) as aa order by count2 desc");
	$su = sum2($fe["count2"]);
	$fe = lfn3($fe); $fe = filter3($fe, "@lfn@ <= $topfilter");
	$fe = comp3($fe, "@pm2@ = format2(@count2@ / $su * 100, '0.0');");
	for ($j = 0; $j < count($vf); ++$j) {
		$c = $vf[$j];
		$fe = comp3($fe, "@$c@ = explode(',', @config@)[$j];");
		$fe = comp3($fe, "@$c@ = preg_replace(array('/1/', '/0/'), array('<b>+</b>', '-'), @$c@);");
	}
	$fe = get3($fe, $v.",pm2,count2");
	$fe = labelheaders3($fe);
	showneu(flip3($fe));
}

//echo getcols($fe[0], "v1,v3");
function getcols($fe, $v, $asfe = 0){ //liefert Spaltennummern
	$vf = explode(",", $v);
	for ($v = 0; $v < count($vf); ++$v) {
		for ($j = 0; $j < count($fe); ++$j) if (preg_match("/^".$vf[$v]."$/", $fe[$j]) > 0) $c[] = $j;
	}
	if ($asfe == 0) return implode(",", $c); else return $c;
}

//echo getvars($fe[0], "v1,v3");
function getvars($fe, $v){ //liefert Variablennamen aus erster Zeile
	$vf = explode(",", $v);
	for ($v = 0; $v < count($vf); ++$v) {
		for ($j = 0; $j < count($fe); ++$j) if (preg_match("/".$vf[$v]."/", $fe[$j]) > 0) $c[] = $fe[$j];
	}
	return implode(",", $c);
}

function fins($fe, $tx = ",", $prae = "", $post = ""){
	for ($i = 0; $i < count($fe); ++$i) $o[] = $fe[$i];
	return $prae.implode($post.$tx.$prae, $o).$post;
}

function findmx($fe){
	$mx = max($fe) + 1;
	for($j = 0; $j < $mx; ++$j) if (!in_array($j, $fe)) return $j;
}

function spalteloeschen($fe, $cols){
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	$li = explode(",", $cols);

	for ($i = 0; $i < $gr1; ++$i) {
		for ($j = 0; $j < $gr2; ++$j) if (inlist3b($li, $j)) unset($fe[$i][$j]);
		$fe[$i] = array_values($fe[$i]);
	}
	return $fe;
}

// $fe = getrnd(10,5); show($fe);
// show(spalteloeschen2($fe, "c[2-3]"));
function spalteloeschen2($fe, $vars){
	$cols = getcols($fe[0], $vars);

	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	$li = explode(",", $cols);

	for ($i = 0; $i < $gr1; ++$i) {
		for ($j = 0; $j < $gr2; ++$j) if (inlist3b($li, $j)) unset($fe[$i][$j]);
		$fe[$i] = array_values($fe[$i]);
	}
	return $fe;
}

//$fe = spaltenvector($fe, "1,2,3");
function spaltenvector($fe, $cols){
	$c = explode(",", $cols);
	$gr1 = count($fe);
	$gr2 = count($fe[0]);

	for ($i = 0; $i < $gr1; ++$i) {
		for ($j = 0; $j < $gr2; ++$j) {
			for ($l = 0; $l < count($c); ++$l) if ($j == $c[$l]) $o[$i][$l] = $fe[$i][$j];
		}
	}
	return $o;
}

function cols_add($v, $tb){spalten_add($v, $tb); }

function spalten_add($v, $tb){ //hängt Spalten an aus 0 und 1, 1 wenn Code von $v zutrifft, sonst 0
	$st = uvstufen2($v, $tb); $stf = explode(",", $st);
 	if (instr($tb, " where ")) $wt = " and "; else $wt = " where ";
	for($j = 0; $j < count($stf); ++$j) {
		$c = $stf[$j];
		comp2($v."_$c = 1", $tb.$wt." $v  = $c");
		comp2($v."_$c = 0", $tb.$wt." $v <> $c");
	}
}

function vector($fe, $var){
	$cols = getcols($fe[0], $var);
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	for ($i = 0; $i < $gr1; ++$i) {
		for ($j = 0; $j < $gr2; ++$j) {
			if ($j == $cols) $o[] = $fe[$i][$j];
		}
	}
	return $o;
}

function zeileloeschen($fe, $r){unset($fe[$r]); return array_values($fe); }

// function mychart
// ________________________ voll funktionierendes Beispiel ___________________
// $fe = getrnd(100, 3); comp($fe, "if (@c1@ < 50) @gruppe@ = 0; else @gruppe@ = 1;");
// boxplot(1, $fe, "gruppe", "c3", $rg = "0,100");
// |
// |  _|_   _|_
// | |   | |   |
// | |   | |   |
// | |_ _| |___|
// |   |     |
// |_____________  
//
function boxplot($neu, $fe, $uv, $av, $rg = "0,100"){ //1 uv + 1 av
	$l = chr(10);
	$db = currdb();
	$ofi = ofi($db, "boxplot_".$uv."_by_".$av).".png";
	
	if ($neu == 1) {
		$uvf = vl($fe[0], $uv); $uv = $uvf[0]; $l1 = givelabel($uv1); $c1 = uvstufen($fe, $uv); $cl1 = givelabel_codes2($fe, $uv);
		$avf = vl($fe[0], $av); $av = $avf[0]; $la = givelabel($av);
		
		$f = "/tmp/tmp.dat"; writefe(getmat2($fe, $uv.",".$av), $f);
		$r .= "library(ggplot2);".$l;
		$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x = subset(x, complete.cases(x));".$l;
		$r .= " x[, '$uv'] <- paste('gr', x[, '$uv'], sep = '');".$l;
		$r .= "gg <- ggplot(x, aes(y = $av, x = $uv, fill = factor($uv))) + geom_boxplot()  +  
			stat_summary(fun.y = mean, geom = 'point', shape = 3, size = 4) + 
			geom_jitter(position = position_jitter(width = .3), size = 1, color = 'grey77') + 
			scale_x_discrete(labels = c($cl1)) + 
			xlab('') + ylab('') +  
			theme(legend.position = 'none', axis.text.y = element_text(size = 12, colour = 'grey33'),axis.text.x = element_text(size = 14));
			ggsave(gg, file = '$ofi', dpi = 600);".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	$ofi2 = $ofi."__".zufallsstring(8);
	exec("rm ".$ofi."__*");
	exec("cp $ofi $ofi2");
 	$fi = fromto($ofi2, "/eigenes/www/$db/", "");
 	echo "<img src='$fi' style = 'height:40%; width:40%;'/>";
}

// function mychart
// ________________________ voll funktionierendes Beispiel ___________________
// $fe = getrnd(100, 3); comp($fe, "@gruppe@ = trunc(@c1@/20);");
// bars0(1, $fe, "gruppe", "0,5,1", "0,30,5");
// |
// |            __
// |       __  |  |
// |  __  |  | |  |
// | |  | |  | |  |
// |_|__|_|__|_|__|
//
function bars1($neu, $fe, $uv, $ylim = "0, 100, 10"){
	$l = chr(10);
	$db = currdb();
	$st = uvstufen($fe, $uv);
	$ofi = ofi($db, "bars1_".$tb."_".$uv.$st).".png";
	if ($neu == 1) {
		$o = freq2d($fe, $uv, 2);
		$f = "/tmp/tmp.dat"; writefe($o, $f);
		$uvf = vl($fe[0], $uv); $uv = $uvf[0]; $l1 = givelabel($uv1); $cl1 = givelabel_codes2($o, $uv);
		$r .= "library(ggplot2);".$l;
		$r .= "x <- read.table('$f', header = TRUE);
			yl = c($ylim)[1:2];
			gg <- ggplot(x, aes(x = factor($uv), y = pr)) + geom_bar(stat = 'identity', fill = 'green', colour = 'grey44') + 
				scale_y_continuous(limits = yl, breaks = seq($ylim)) +
				scale_x_discrete(labels = c($cl1)) +
				ylab('Prozent') + xlab('') + 
				theme(axis.text.x = element_text(size = 11, colour = 'grey33'), axis.ticks = element_blank(), axis.text.y = element_text(size = 14));;
			ggsave(gg, file = '$ofi', dpi = 600);".$l;
			$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	$ofi2 = $ofi."__".zufallsstring(3);
	exec("rm ".$ofi."__*");
	exec("cp $ofi $ofi2");
 	$fi = fromto($ofi2, "/eigenes/www/$db/", "");
	echo "<img src='$fi' style = 'height:40%; width:40%;'/>";
}

// $fe = getrnd(100, 3); comp($fe, "@gruppe@ = trunc(@c1@/20);");
// bars0(1, $fe, "gruppe", "0,5,1", "0,30,5");
function bars0($neu, $fe, $uv, $xlim = "0, 60, 10", $ylim = "0, 100, 10", $pngadd = ""){ //freq als plot
	$l = chr(10);
	$db = currdb();
	$st = uvstufen($fe, $uv);
	#$ofi = ofi($db, "bars0_".$tb."_".preg_replace("/ /", "", $xlim)."_".preg_replace("/ /", "", $ylim).$uv).".png";
	$ofi = ofi($db, "bars0_".$pngadd).".png";
	if ($neu == 1) {
		$o = freq2c($fe, $uv, 1);
		#show($o);
		$f = "/tmp/tmp.dat"; writefe($o, $f);
		$uvf = vl($fe[0], $uv); $uv = $uvf[0]; $l1 = givelabel($uv1); $cl1 = givelabel_codes2($fe, $uv);
		$r .= "library(ggplot2);".$l;
		$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x = subset(x, complete.cases(x));
			xl = c($xlim)[1:2]; yl = c($ylim)[1:2];
			gg <- ggplot(data = x, aes(x = $uv, y = pr, width = .8)) + geom_bar(stat = 'identity', position = 'identity', fill = 'green', colour = 'white') +
				scale_y_continuous(limits = yl, breaks = seq($ylim)) +	scale_x_continuous(limits = xl, breaks = seq($xlim), labels = seq($xlim)) + ylab('Percent') + xlab('') + 
				theme(axis.text.y = element_text(size = 14, colour = 'grey33'),axis.text.x = element_text(size = 14), axis.ticks = element_blank(), axis.text.x = element_text(size = 14));
			ggsave(gg, file = '$ofi', dpi = 600); rm(gg); rm(x);".$l;
			$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
			#$fi = "/tmp/r.cmd"; write2($r, $fi); exec("R --no-save --slave -q < $fi > $fi.out");
	}
	$ofi2 = $ofi; #."__".zufallsstring(8);
	exec("rm ".$ofi."__*");
	exec("cp $ofi $ofi2");
 	$fi = fromto($ofi2, "/eigenes/www/$db/", "");
	echo chr(10)."<img src='$fi' style = 'height:40%; width:40%;'/>";
}

// function mychart
// ________________________ voll funktionierendes Beispiel ___________________
// $fe = getrnd(1200, 6); for($j = 1; $j <=5; ++$j) comp($fe, "if (@c$j@ < 50) @c$j@ = 0; else @c$j@ = 1;");
// bars_odds(1, $fe, "^c1$", "^c[2-5]$");
// |
// |            __
// |  __   __  |  |
// |_|__|_|__|_|__|
// |
// |
//
function bars_odds($neu, $fe, $one_uv1, $many_uv2, $yrg = "0,2"){
	$l = chr(10);
	$db = currdb();
	$ofi = ofi($db, "bars_odds_".$tb."_".$uv1."_by_".$uv2."_with_".$av).".png";
	
	if ($neu == 1) {	
		$uvf1 = vl($fe[0],  $one_uv1); $uv1 = implode(",", $uvf1);
		$uvf2 = vl($fe[0], $many_uv2); $uv2 = implode(",", $uvf2);
		
		for($j = 0; $j < count($uvf2); ++$j) {
			$od = r_odds($fe, $uvf1[0], $uvf2[$j]);
			comp($od, "@var@ = '".$uvf2[$j]."';");
			if ($j == 0) $o[] = $od[0];
			$o[] = $od[1];
		}

		comp($o, "@l@ = givelabel(@var@);");
		$o = function_on_fe($o, "^odds|^p|lo|up", "format2(@, '0.0000')");
		$o = function_on_fe($o, "^p", "format2(@, '0.0000')");
		$f = "/tmp/tmp.dat"; writefe($o, $f);
		
		$r .= "x <- read.table('$f', header = TRUE);".$l;
		$r .= "x['er_l'] <- x\$odds - x\$lower;".$l;
		$r .= "x['er_u'] <- x\$upper - x\$odds;".$l;
		$r .= " x[, 'l'] <- paste(x[, 'l'], '\rp = ', x[, 'p']);".$l;
		$r .= "library(ggplot2);".$l;
		$r .= "gg <- ggplot(x, aes(x = x\$var, y = x\$odds - 1)) + 
			geom_bar(stat = 'identity', fill = 'blue', colour = 'grey33') + 
			geom_errorbar(aes(ymin = x\$odds - 1 - x\$er_l, ymax = x\$odds - 1 + x\$er_u), width = 0.25) + 
			scale_y_continuous(limits = c($yrg) - 1, breaks = seq($yrg, by = .25) - 1, labels = seq($yrg, by = .25)) + 
			scale_x_discrete(limits = rev(x\$var), labels = rev(x\$l)) +
			geom_hline(yintercept = 0, colour = 'grey20', size = .45) + xlab('') + ylab('') + 
			theme(axis.ticks = element_blank(),axis.text.y = element_text(size = 12, colour = 'grey33'),axis.text.x = element_text(size = 14));".$l;
		$r .= "gg <- gg + coord_flip();".$l;
		$r .= "ggsave(gg, file = '$ofi', dpi = 600); ".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi);
		$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	$ofi2 = $ofi."__".zufallsstring(8);
	exec("rm ".$ofi."__*");
	exec("cp $ofi $ofi2");
 	$fi = fromto($ofi2, "/eigenes/www/$db/", "");
	echo "<img src='$fi' style = 'height:40%; width:40%;' >";
}

//function mychart
//  coxregression_single(1, $tb, "zeit", "event", "^v[1-5]$", 1);
// ________________________ voll funktionierendes Beispiel ___________________
// $fe = getrnd(12, 6); for($j = 1; $j <=5; ++$j) comp($fe, "if (@c$j@ < 50) @c$j@ = 0; else @c$j@ = 1;");
// $tb = "tmp"; push($fe, $tb); coxregression_single(1, $tb, "c6", "c1", "^c[2-5]$", 1);
// oooooo    #odds quer
// oooo
// oo
// oooooooo
function bars_odds_out_of_cox($neu, $fe, $name, $rg = "0,16"){ // Bars für die Odds-Ratio
	$l = chr(10);
	$db = currdb();
	$otb = ofi($db, $name).".bar.txt";
	$ofi = ofi($db, $name).".bar.png";
	if ($neu == 1) {
		$fe = lfn($fe);
		comp($fe, "@l@ = givelabel(@av@).'\r'.sign(@p@, 4);");
		$fe = function_on_fe($fe, "^OR|^p|ci", "format2(@, '0.00')");
		writefe($fe, $otb, 1);
		$r .= "x <- read.table('$otb', header = TRUE);".$l;
		$r .= "x['er_l'] <- x\$OR - x\$lci;".$l;
		$r .= "x['er_u'] <- x\$uci - x\$OR;".$l;
		$r .= "library(ggplot2);".$l;
		$r .= "gg <- ggplot(x, aes(x = x\$av, y = x\$OR - 1)) + 
			geom_bar(stat = 'identity', fill = 'blue', colour = 'grey33') + 
			geom_errorbar(aes(ymin = x\$OR - 1 - x\$er_l, ymax = x\$OR - 1 + x\$er_u), width = 0.25) +
			scale_y_continuous(limits = c($rg) - 1, breaks = seq($rg, by = 1) - 1, labels = seq($rg, by = 1)) +
			scale_x_discrete(limits = rev(x\$av), labels = rev(x\$l)) +
			geom_hline(yintercept = 0, colour = 'grey20', size = .45) + xlab('') + ylab('') + 
			theme(axis.ticks = element_blank(),axis.text.x = element_text(size = 12, colour = 'grey33'));".$l;
		$r .= "gg <- gg + coord_flip();".$l;
		$r .= "ggsave(gg, file = '$ofi', dpi = 600); ".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi);
		$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	$ofi2 = $ofi."__".zufallsstring(8);
	exec("rm ".$ofi."__*");
	exec("cp $ofi $ofi2");
 	$fi = fromto($ofi2, "/eigenes/www/$db/", "");
	echo $l."<img src='$fi' style = 'height:40%; width:40%;' >";
}

// function mychart
// ________________________ voll funktionierendes Beispiel ___________________
// $fe = getrnd(120, 5); comp($fe, "if (@c1@ < 50) @gr1@ = 0; else @gr1@ = 1;"); comp($fe, "if (@c2@ < 50) @gr2@ = 0; else @gr2@ = 1;");
// meanseq(1, $fe, "gr1$", "gr2$", "c3$", "30, 80");
function meanseq($neu, $fe, $uv1, $uv2, $av, $yrg){ 				// 2 UV z.B. Agegroup (x-Achse) x Gender (Legende) und BMI (y-Achse), means und SD, install.packages("ggplot2");
	if ($rg !== "") $yrg = "scale_y_continuous(limits = c($yrg)) + ";	// http://www.cookbook-r.com/Graphs/Plotting_means_and_error_bars_%28ggplot2%29/
	$l = chr(10);								// install.packages("ggplot2"); install.packages("reshape");
	$db = currdb();
	$ofi = ofi($db, "meanseq_".$tb."_".$uv1."_by_".$uv2."_with_".$av).".png";

	$uvf1 = vl($fe[0], $uv1); $uv1 = $uvf1[0]; $l1 = givelabel($uv1); $c1 = uvstufen($fe, $uv1); $cl1 = givelabel_codes2($fe, $uv1);
	$uvf2 = vl($fe[0], $uv2); $uv2 = $uvf2[0]; $l2 = givelabel($uv2); $c2 = uvstufen($fe, $uv2); $cl2 = givelabel_codes2($fe, $uv2);
	$avf  = vl($fe[0],  $av); $av  =  $avf[0]; $la = givelabel($av);
	
	if ($neu == 1) {
		$fe = agg($fe, "$uv1,$uv2", $av, "mean2,se2,count2");
		$f = "/tmp/tmp.dat"; writefe($fe, $f);
		#show($fe);
		
		$mean = $av."mean2"; $sd = $av."se2";
		$r .= "y <- read.table('$f', header = TRUE); y[] <- lapply(y, as.numeric); y[,'$uv2'] <- paste('gr', y[,'$uv2'], sep = '');".$l;
		$r .= "png(file = '$ofi');".$l;
		$r .= "library(ggplot2);".$l;
		$r .= "pd <- position_dodge(.05); palette <- c('red', 'blue', 'green', 'yellow', 'black', 'grey30', 'grey50', 'grey70', 'grey90');".$l;
		
		$r .= "gg <- ggplot(y, aes(x = $uv1, group = $uv2, y = $mean, colour = $uv2, ymax = max($mean) * 1.1)) + 
			geom_line(position = pd) +  
			geom_errorbar(aes(ymin = $mean - $sd, ymax = $mean + $sd), width = .1, position = pd) + 
			geom_point(position = pd, size = 5, shape = 21, fill = 'white') + 
			
			xlab('') + ylab('') + $yrg		
 			scale_x_continuous(limits = c(levels(y\$$uv1)), breaks = c($c1), labels = c($cl1)) + 
 			scale_colour_manual(values = palette, labels = c($cl2)) +
			
			theme(legend.justification = c(1,0), legend.position = c(1,0),
				legend.title = element_blank(), 
				legend.text = element_text(size = 14), 
				axis.text.x = element_text(size = 14, colour = 'black'), 
				axis.text.y = element_text(size = 14, colour = 'black')
				);".$l;
		
		$r .= "ggsave(gg, file = '$ofi', dpi = 600); ".$l;
		$fi = "/tmp/r.cmd";
		$fi = "/tmp/r.cmd"; write2($r, $fi); exec("R --no-save --slave -q < $fi > $fi.out");
		#echop("<textarea id=mytextarea rows=27 cols=210 wrap=off>$r</textarea> ");
	}
	$ofi2 = $ofi."__".zufallsstring(8);
	exec("rm ".$ofi."__*");
	exec("cp $ofi $ofi2");
 	$fi = fromto($ofi2, "/eigenes/www/$db/", "");
	echo "<img src='$fi' style = 'height:40%; width:40%;' >";
}

//function mychart
// ________________________ voll funktionierendes Beispiel ___________________
// $fe = getrnd(120, 5); comp($fe, "if (@c1@ < 50) @gr1@ = 0; else @gr1@ = 1;"); comp($fe, "if (@c2@ < 50) @gr2@ = 0; else @gr2@ = 1;");
// meanseq_with_areas(1, $fe, "gr1$", "gr2$", "c3$", "30, 80");
function meanseq_with_areas($neu, $fe, $uv1, $uv2, $av, $yrg = "0,100", $xrg = "0,1", $ti = "Area Plot"){ //Mittelwert im Verlauf mit SD als Flächen
	$l = chr(10);
	$db = currdb();
	$ofi = ofi($db, "meanseq_".$tb."_".$uv1."_by_".$uv2."_with_".$av).".png";
	
	if ($neu == 1) {
		$uvf1 = vl($fe[0], $uv1); $uv1 = $uvf1[0]; $l1 = givelabel($uv1); $c1 = uvstufen($fe, $uv1); $cl1 = givelabel_codes2($fe, $uv1);
		$uvf2 = vl($fe[0], $uv2); $uv2 = $uvf2[0]; $l2 = givelabel($uv2); $c2 = uvstufen($fe, $uv2); $cl2 = givelabel_codes2($fe, $uv2);
		$avf  = vl($fe[0],  $av); $av  =  $avf[0]; $la = givelabel($av);

		$fe = agg($fe, "$uv1,$uv2", $av, "mean2,se2,count2");
		show($fe);
		$uv1l = givelabel($uv1); $uv2l = givelabel($uv2);
		$f = "/tmp/tmp.dat"; writefe($fe, $f);
		
		$mean = $av."mean2"; $sd = $av."se2";
		$r .= "y <- read.table('$f', header = TRUE); y[] <- lapply(y, as.numeric); y[,'$uv2'] <- paste('gr', y[,'$uv2'], sep = '');".$l;
		$r .= "library(ggplot2);".$l;
		$r .= "gg <- ggplot(y, aes(x = $uv1, y = $mean)) + geom_line(aes(colour = $uv2)) + xlab('$uv1l') + ylab('$uv2l') + ggtitle('$ti') + 
				geom_ribbon(aes(ymax = $mean + $sd, ymin = $mean - $sd, fill = $uv2), alpha = 0.2) + 
				scale_colour_manual(values = c('red', 'green', 'blue', 'yellow')) + scale_fill_manual(values = c('red', 'green', 'blue', 'yellow')) + 
				scale_y_continuous(limits=c($yrg)) + 
				scale_x_continuous(limits = c($xrg), breaks = c(0.1, 0.9), labels = c($cl1)) + 
				
				theme(legend.justification = c(1,0), legend.position = c(0.9,0.8),
					legend.title = element_blank(), 
					legend.text = element_text(size = 14), 
					axis.text.x = element_text(size = 14, colour = 'black'), 
					axis.text.y = element_text(size = 14, colour = 'black')
				);".$l;
		$r .= "ggsave(gg, file = '$ofi', dpi = 600); rm(list = ls());".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	$ofi2 = $ofi."__".zufallsstring(8);
	exec("rm ".$ofi."__*");
	exec("cp $ofi $ofi2");
 	$fi = fromto($ofi2, "/eigenes/www/$db/", "");
	echo "<img src='$fi' style = 'height:40%; width:40%;' >";
}


// function mychart
// ________________________ voll funktionierendes Beispiel ___________________
// $fe = getrnd(250, 5);
// $v = "c1"; $o = "gr";
// comp($fe, "switch (1){case (@$v@ >=  0 and @$v@ <=  30): @$o@ = 1; break;
// 			 case (@$v@ >  30 and @$v@ <=  50): @$o@ = 2; break;
// 			 case (@$v@ >  50 and @$v@ <= 100): @$o@ = 3; break;
// 			};");
// meanseq_repeated(1, $fe, "gr", "^c[234]$", "30,80");
function meanseq_repeated($neu, $fe, $uv, $av, $yrg){			 	// 2 UV (z.B. 2 Gruppen x pre-posttest)
	if ($yrg <> "") $yrg = "scale_y_continuous(limits = c($yrg)) + ";	// http://www.cookbook-r.com/Graphs/Plotting_means_and_error_bars_%28ggplot2%29/
	$l = chr(10);								// install.packages("ggplot2"); install.packages("reshape");
	$db = currdb();
	$ofi = ofi($db, "meanseq_repeated_".$tb."_".$uv."_with_".$av).".jpg";
	
	if ($neu == 1) {
		if (!var_da($fe, "^lfn$")) $fe = lfn($fe);
		$fe = getmat2($fe, "lfn,".$uv.",".$av);
		$fe = selif3($fe, "trim(@gr@) !== ''");
		comp($fe, "@gr@ = 'gr'.@gr@;");
		
		$f = "/tmp/tmp.dat"; writefe($fe, $f);
		
		$avf = vl($fe[0], $av); $av = implode(",", $avf); $avgr = count($avf); $avl = label_list($avf);
		$av2  = "'".implode("','", $avf)."'";
		$avl2 = "'".implode("','", $avl)."'";
		$cl = givelabel_codes2($fe, $uv);
		
		$r .= "y <- read.table('$f', header = TRUE);".$l;
		$r .= "library(reshape); y2 <- melt(y, id.vars = c('$uv', 'lfn'), measure.vars = c($av2), variable.name = 'value');".$l;
		$r .= "y3mean <- aggregate(y2[, -c(1,2,3)], list(y2\$$uv, y2\$variable), mean2); y3se <- aggregate(y2[, -c(1,2,3)], list(y2\$$uv, y2\$variable), se2)".$l;
		$r .= "colnames(y3mean) <- c('gruppe', 'messwd', 'mean');".$l;
		$r .= "colnames(y3se  ) <- c('gruppe', 'messwd', 'se'  );".$l;
		$r .= "y4 <- merge(y3mean, y3se, by = c('gruppe', 'messwd'));".$l;
		
		$r .= "png(file = '$ofi');".$l;
		$r .= "library(ggplot2);".$l;
		$r .= "pd <- position_dodge(.1); palette <- c('red', 'blue', 'green', 'yellow', 'black', 'grey30', 'grey50', 'grey70', 'grey90')".$l;

		$r .= "gg <- ggplot(y4, aes(x = messwd, y = mean, group = gruppe, colour = gruppe, ymax = max(mean) * 1.1)) + 

			geom_line(position = pd) + 
			geom_errorbar(aes(ymin = mean - se, ymax = mean + se), width = .1, position = pd) +
			geom_point(position = pd, size=5, shape=21, fill = 'white') + $yrg 
			
			xlab('') + ylab('') + scale_x_discrete(breaks = c($av2), labels = c($avl2)) +
			scale_colour_manual(values = palette, labels = c($cl)) +
			
			theme(legend.justification = c(1,0), legend.position = c(1, 0.8),
				legend.title = element_blank(), 
				legend.text = element_text(size = 14), 
				axis.text.x = element_text(size = 14, colour = 'black'), 
				axis.text.y = element_text(size = 14, colour = 'black')
			);".$l;
		$r .= "ggsave(gg, file = '$ofi', dpi = 600); ".$l;				
		$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
		#echop("<textarea id=mytextarea rows=27 cols=210 wrap=off>$r</textarea> ");
	}
	$ofi2 = $ofi."__".zufallsstring(8);
	exec("rm ".$ofi."__*");
	exec("cp $ofi $ofi2");
 	$fi = fromto($ofi2, "/eigenes/www/$db/", "");
	echo "<img src='$fi' style = 'height:40%; width:40%;' >";
}

// function mychart
// |            __
// |       __  |  |
// |  __  |  | |  |
// | |  | |  | |  |
// |_|__|_|__|_|__|
// $fe = getrnd(100, 5); avbars(1, $fe, "^c[1-5]$", "0,80");
function avbars($neu, $fe, $av, $yrg){ //mehrere av nebeneinander als Balken, keine uv
	if ($yrg <> "") $yrg = "scale_y_continuous(limits = c($yrg)) + ";

	$l = chr(10);
	$db = currdb();
	$ofi = ofi($db, "avbars_".$tb."_".$av).".png";

	if ($neu == 1) {
		if (!var_da($fe, "^lfn$")) $fe = lfn($fe);
		$fe = getmat2($fe, "lfn,".$av);
		$f = "/tmp/tmp.dat"; writefe($fe, $f);
		
		$avf = vl($fe[0], $av); $av = implode(",", $avf); $avgr = count($avf); $avl = label_list($avf);
		$av2  = "'".implode("','", $avf)."'";
		$avl2 = "'".implode("','", $avl)."'";
		
		$r .= "y <- read.table('$f', header = TRUE); y[] <- lapply(y, as.numeric);".$l;
		$r .= "library(reshape);".$l;
		$r .= "y2 <- melt(y, id.vars = c('lfn'), measure.vars = c($av2), variable.name = 'value');".$l;
		$r .= "y3mean <- aggregate(y2[, -c(1,2)], list(y2\$variable), mean2); y3sd <- aggregate(y2[, -c(1,2)], list(y2\$variable), se2)".$l;
		$r .= "colnames(y3mean) <- c('messwd', 'mean'); colnames(y3sd) <- c('messwd', 'sd');".$l;
		$r .= "y4 <- merge(y3mean, y3sd, by = c('messwd'));".$l;
		$r .= "y4 <- y4[ order(-y4[,2]), ];".$l;
		$r .= "library(ggplot2);".$l;
		$r .= "gg <- ggplot(data = y4, aes(x = messwd, y = mean, fill = messwd)) + 
				geom_bar(colour = 'black', fill = 'blue', width = .8, stat = 'identity') + $yrg 
				geom_errorbar(aes(ymin = mean - sd, ymax = mean + sd), width = .1) + xlab('') + ylab('') +
			theme(axis.text.x = element_text(size = 14, colour = 'black'), 
				axis.text.y = element_text(size = 14, colour = 'black')) + 
	 			scale_x_discrete(labels = c($avl2));";
		$r .= "ggsave(gg, file = '$ofi', dpi = 600); rm(list = ls());".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	$ofi2 = $ofi."__".zufallsstring(8);
	exec("rm ".$ofi."__*");
	exec("cp $ofi $ofi2");
 	$fi = fromto($ofi2, "/eigenes/www/$db/", "");
	echo "<img src='$fi' style = 'height:40%; width:40%;' >";
}

// function mychart
// |    O
// |    OM  M
// | OM OM OM
// |_OM_OM_OM__
// ________________________ voll funktionierendes Beispiel ___________________
// $fe = getrnd(100, 5);
// comp($fe, "if (@c1@ < 50) @gr@ = 0; else @gr@ = 1;");
// avbars_by_uv(1, $fe, "^c[2-5]$", "gr", "0,80");
function avbars_by_uv($neu, $fe, $av, $uv, $yrg){ //mehrere av nebeneinander als Balken, eine uv, als Balken pre-posttest und 2 Gruppen = 4 Säulen
	if ($yrg <> "") $yrg = "scale_y_continuous(limits = c($yrg)) + ";	
	$l = chr(10);
	$db = currdb();
	$ofi = ofi($db, "avbars_".$tb."_".$av."_by_".$uv).".png";

	if ($neu == 1) {
		if (!var_da($fe, "^lfn$")) $fe = lfn($fe);
		$fe = getmat2($fe, "lfn,".$uv.",".$av);
		
		$f = "/tmp/tmp.dat"; writefe($fe, $f);
		$avf = vl($fe[0], $av); $av = implode(",", $avf); $avgr = count($avf); $avl = label_list($avf); 
		
		$av2  = "'".implode("','", $avf)."'";
		$avl2 = "'".implode("','", $avl)."'";
		
		$r .= "y <- read.table('$f', header = TRUE); y[] <- lapply(y, as.numeric);".$l;
		$r .= "library(reshape);".$l;
		$r .= "y2 <- melt(y, id.vars = c('lfn', '$uv'), measure.vars = c($av2), variable.name = 'value');".$l;
		$r .= "y3mean <- aggregate(y2[, -c(1,2,3)], list(y2\$variable, y2\$$uv), mean2);".$l;
		$r .= "y3sd   <- aggregate(y2[, -c(1,2,3)], list(y2\$variable, y2\$$uv), se2)".$l;
		$r .= "colnames(y3mean) <- c('messwd', '$uv', 'mean'); colnames(y3sd) <- c('messwd', '$uv', 'sd');".$l;
		$r .= "y4 <- merge(y3mean, y3sd, by = c('messwd', '$uv'));".$l;
		$r .= "y4\$$uv <- factor(y4\$$uv);".$l;
		$r .= "library(ggplot2);".$l;
		$r .= "gg <- ggplot(y4, aes(x = messwd, y = mean, fill = $uv)) + 
				geom_bar(position=position_dodge(), stat = 'identity') + xlab('') + ylab('') +
				geom_errorbar(aes(ymin = mean - sd, ymax = mean + sd), width=.2, position=position_dodge(.9))+ 
				theme(axis.text.x = element_text(size = 14, colour = 'black'), 
					axis.text.y = element_text(size = 14, colour = 'black')) + 
	 			scale_x_discrete(labels = c($avl2) );".$l;
		$r .= "ggsave(gg, file = '$ofi', dpi = 600); rm(list = ls());".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	$ofi2 = $ofi."__".zufallsstring(8);
	exec("rm ".$ofi."__*");
	exec("cp $ofi $ofi2");
 	$fi = fromto($ofi2, "/eigenes/www/$db/", "");
	echo "<img src='$fi' style = 'height:40%; width:40%;' >";
}

// function mychart
// _____________ voll funktionierendes Beispiel ___________________
// $fe = getrnd(100, 10);
// comp($fe, "if (@c1@ < 50) @gr1@ = 0; else @gr1@ = 1;");
// comp($fe, "if (@c2@ < 50) @gr2@ = 0; else @gr2@ = 1;");
// bars_uv1_by_uv2_with_av(1, $fe, "gr1", "gr2", "^c[4]$", "0,80");
function bars_uv1_by_uv2_with_av($neucalc, $fe, $uv1, $uv2, $av, $yrg = "0,100"){ //r x c Design und 1 av als Balken-mean, z.B. 3 Altersgruppen x Geschlecht und BMI als av = 6 Säulen
	$fe = getmat2($fe, $uv1.",".$uv2.",".$av);
	$f = "/tmp/tmp.dat"; writefe($fe, $f);
	
	$l = chr(10);
	$db = currdb();
	$ofi = ofi($db, "bars_uv1_by_uv2_with_av_".$uv1."_".$uv2."_".$av).".png";

	if ($neucalc == 1) {
		$av = vl($fe[0], $av); $lu = givelabel($uv1); $cl = givelabel_codes2($fe, $uv1);
		$r .= "y <- read.table('$f', header = TRUE); y[] <- lapply(y, as.numeric); y <- subset(y, complete.cases(y));".$l;
		$r .= "y3mean <- aggregate(y[, -c(1,2)], list(y\$$uv1, y\$$uv2), mean2);".$l;
		$r .= "y3sd   <- aggregate(y[, -c(1,2)], list(y\$$uv1, y\$$uv2), se2)".$l;
		$r .= "colnames(y3mean) <- c('$uv1', '$uv2', 'mean'); colnames(y3sd) <- c('$uv1', '$uv2', 'sd');".$l;
		$r .= "y4 <- merge(y3mean, y3sd, by = c('$uv1', '$uv2'));".$l;
		$r .= "y4\$$uv1 <- factor(y4\$$uv1);".$l;
		$r .= "y4\$$uv2 <- factor(y4\$$uv2);".$l;
		$r .= "library(ggplot2);".$l;
		$r .= "gg <- ggplot(y4, aes(x = $uv1, y = mean, fill = $uv2)) + 
				geom_bar(position=position_dodge(), stat = 'identity') + xlab('') + ylab('') +
				geom_errorbar(aes(ymin = mean - sd, ymax = mean + sd), width=.2, position=position_dodge(.9))+ 
				theme(legend.justification = c(1,0), legend.position = c(1, 0.8),legend.title = element_blank(), legend.text = element_text(size = 14), axis.text.x = element_text(size = 14, colour = 'black'), 
					axis.text.y = element_text(size = 14, colour = 'black'), axis.ticks.x = element_blank()) +
				scale_y_continuous(limits = c($yrg)) + 
	 			scale_x_discrete(labels = c($cl));".$l;
		$r .= "ggsave(gg, file = '$ofi', dpi = 600); rm(list=ls());".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	$ofi2 = $ofi."__".zufallsstring(8);
	exec("rm ".$ofi."__*");
	exec("cp $ofi $ofi2");
 	$fi = fromto($ofi2, "/eigenes/www/$db/", "");
	echo "<img src='$fi' style = 'height:40%; width:40%;' >";
}

function kmvalue($fe, $zeit, $event, $gruppe, $zeitpunkte = "7,14,21,28"){
	$l = chr(10);
	$ofi = "/eigenes/downs/temp/kmvalue.out"; if (file_exists($ofi)) unlink ($ofi);
	$fe = data::get($fe, $zeit."$,^".$event."$,^".$gruppe."$");
	$f = "/eigenes/downs/temp/tmp.dat"; export::asc($fe, $f);
	$st = data::uvstufen($fe, $gruppe);
	
	$u = $stf[0];
	$r .= "require(survival);".$l;
	$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
	
	for($j = 0; $j < count($st); ++$j){
		$u = $st[$j];
		$r .= $l."fit <- survfit(Surv($zeit, $event) ~ $gruppe, subset(x, $gruppe %in% $u), conf.type = 'plain');".$l;
		$r .= "y <- summary(fit, times = c($zeitpunkte));".$l;
		$r .= "y <- data.frame(cbind('$u', y\$time, round(1 - y\$surv,3) * 100));".$l;
		$r .= "colnames(y) <- c('gr', 'zeit', 'surv');".$l;
 		if ($j == 0) $r .= "y2 <- y;".$l; else $r .= "y2 <- rbind(y2, y);".$l;
	}
	$r .= "l <- survdiff(Surv($zeit, $event) ~ $gruppe, x);".$l;
	$r .= "pval <- pchisq(l\$chisq, length(l\$n) - 1, lower.tail = FALSE);".$l;
	$r .= "y2b <- data.frame(cbind('99', 99, pval));".$l;
	$r .= "colnames(y2b) <- c('gr', 'zeit', 'surv');".$l;	
	$r .= "y3 <- rbind(y2, y2b);".$l;
	$r .= "write.table(y3, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); rm(fit);".$l;
	$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s); 			
	$fe = read3d($ofi);
	$fe = data::comp($fe, "@alle@ = 1;");
	$fe = data::comp($fe, "@index@ = @gr@.'_'.format2(@zeit@, '00');");
	$fe = data::filter($fe, "@gr@ <> '99'");
	$fe = data::casestovars($fe, "alle", "index", "surv"); 
	$fe = data::rename($fe, "/surv/", "su");
	$fe = data::rename($fe, "/su_99_99/", "p_km");
	return $fe;
}

// $v = "agec,ge3,bmi30,v8c,v9c,v10c,v11c"; 
// $fe = getmat3("select 1 as alle,lfn,zeit,tod,v63,$v from config");
// $vf = explode(",", $v);
// km_cox_log_tab(0, $fe, "zeit", "tod", "v63", $v);
function km_cox_log_tab($neu, $fe3, $zeit, $tod_cox, $tod_log, $v){
	$vf = vl3($fe3, $v);
	$el = implode(",", array_slice($vf, 0, 5));
	$db = currdb();
	$na = "km_cox_log_tab_".$el;
	$ofi = ofi($db, $na);
	if ($neu == 1) {
		$fe2 = flip3($fe3);
		for ($j = 0; $j < count($vf); ++$j) {
			$a = $vf[$j]; # echo $a;

			$d = desc3($fe3, $a, $fu = "pm2,sum2", 1, 0); $d = comp3($d, "@alle@ = 1;"); $d = flip3($d); #show($d);
			$k = kmvalue($fe2, $zeit, $tod_cox, $a); #show($k);
			
			$c = coxvalue($fe2, $zeit, $tod_cox, $a); #show($c);
			$l = logisticvalue($fe2, $tod_log, $a); #show($l);

			$m = means3($fe3, $a, $tod_log, "pm2,sum2,count2", 0); $m = comp3($m, "@alle@ = 1;"); $m = flip3($m);
			$m = casestovars($m, "alle", $a, $tod_log);
			
			$t = merge(array($d, $k, $m, $c, $l), "alle");
			#show($t);
			
			$t = getmat2($t, "av$,\%,^n$,su_1,su_0,[pr]_cox,ci_c,n_c,p_l,or_l,ci_l,n_l,".$tod_log."_1_p,".$tod_log."_0_p");
			
			if ($j == 0) $t2 = $t; else $t2 = addarr($t2, $t);
			#show($t);
		}
		$t2 = function_on_fe($t2, "^p_|^or_|ci_", "format2(@, '0.00')");
		$t2 = function_on_fe($t2, "\%|^su_", "format2(@, '0')");
		writefe($t2, $ofi);
	}

	$t3 = read2d($ofi);
	#show($t3);
	$t3 = selif3($t3, "@av@ !== 'av'");
	comp($t3, "if (@p_cox@ <= 0.05) @p_cox@ = '<b>'.@p_cox@.'</b>';");
	comp($t3, "if (@p_log@ <= 0.05) @p_log@ = '<b>'.@p_log@.'</b>';");
	$t3 = labelheaders($t3);
	$t3[0][0] = "";
	show($t3, "1,2", "350,50,50,50,50,50,50,50,50,60", "", 
		"<tr>
			<td></td><td ".cs(2).">Prävalenz</td><td ".cs(4).">Sterbe-Inzidenz für (+) / ja<br>(Kaplan-Meier)</td>
			<td ".cs(4).">Inzidenz für (-) / nein<br>(Kaplan-Meier)</td><td ".cs(5).">Cox Regression<br>(Überleben insgesamt)</td>
			<td ".cs(5)." >Logistische Regression<br>(30-Tages-Überleben)</td>
			<td ".cs(2).">Sterbe-inzidenz an 30. postop. Tag</td>
		</tr>");
}

function uvstapel3($neu, $fe, $v, $ofi){
	$vf = vl3($fe, $v);
	$el = implode(",", array_slice($vf, 0, 5));
	$db = currdb();
	$na = "uvstapel_".$el;
	if ($ofi == "") $ofi = ofi($db, $na); else $ofi = ofi($db, $ofi);
	if ($neu == 1) {
		$o0[0][0] = "var"; $o0[0][1] = "n"; $o0[0][2] = "proz";
		for ($j = 0; $j < count($vf); ++$j) {
			$a = $vf[$j];
			$o = freq3($fe, $a, 0, 0); $o[0][0] = "<b>".$o[0][0]."</b>";
			if ($j == 0) $o2 = addarr($o0, $o); else $o2 = addarr($o2, $o);
		}
		$o2 = comp($o2, "if (@n@ == 'n') @n@ = '';");
		$o2 = comp($o2, "if (@proz@ == 'proz') @proz@ = '';");
		writefe($o2, $ofi);
		show($o2);
	}
	$o2 = read2d($ofi);
	showneu($o2, "1,1,3", "250,70");
	$o3 = read3d($ofi); writexls3($o3, $ofi.".xls", "40,10,8", "1,1,3"); showxls($ofi.".xls");
}

function uvstapel3_sik($neu, $fe, $v, $ofi){
	$vf = vl3($fe, $v);
	$el = implode(",", array_slice($vf, 0, 5));
	$db = currdb();
	$na = "uvstapel_".$el;
	if ($ofi == "") $ofi = ofi($db, $na); else $ofi = ofi($db, $ofi);
	if ($neu == 1) {
		for ($j = 0; $j < count($vf); ++$j) {
			$a = $vf[$j]; #md($a);
			$o = freq3($fe, $a, 0, 0);
			$o = comp($o, "@varlabel@ = '';");
			$o[1][3] = $o[0][0]; $o[0][0] = "var";
			if ($j == 0) $o2 = $o; else $o2 = addarr($o2, $o);
		}
		$o2 = getmat2($o2, "varlabel$,var$,n,proz");
		$o2 = selif3($o2, "@varlabel@ <> 'varlabel'");
		writefe($o2, $ofi);
	}
	$o2 = read2d($ofi);
	$o2[0][0] = ""; $o2[0][1] = ""; showneu($o2, "1,1,3", "400,350,50");
	$o3 = read3d($ofi);
	writexls3($o3, $ofi.".xls", "60,40,10", "1,1,3"); #showxls($ofi);
}

function coxvalue($fe, $zeit, $event, $cov){
	$l = chr(10);
	$ofi = "/eigenes/downs/temp/coxvalue".$cov.".out";
	$fe = data::get($fe, $zeit.",".$event.",".$cov);
	$f = "/eigenes/downs/temp/coxtmp.".$cov.".dat"; export::asc($fe, $f);
	$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric);".$l;
	$r .= "require(survival);".$l;
	$r .= "c1 <- coxph(formula = Surv($zeit, $event) ~ $cov, data = x);".$l;
	$r .= "c2 <- summary(c1);".$l;
	$r .= "c3 <- data.frame(cbind(c2\$coefficients, c2\$conf.int))[, c(5,6,8,9)];".$l;
	$r .= "n <- nrow( data.frame(c1[7]));".$l;			
	$r .= "c4 <- data.frame(cbind(rownames(c3), c3, c1\$nevent, n));".$l;
	$r .= "colnames(c4) <- c('var_cox', 'p_cox', 'or_cox', 'lci_cox', 'uci_cox', 'nev_cox', 'n_cox');".$l;
	$r .= "cc <- c4;".$l;
	$r .= "write.table(cc, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); ".$l;
	#$fi = "/eigenes/downs/temp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	$fi = "/eigenes/downs/temp/r.cox.".$cov.".cmd"; write2($r, $fi); exec("chmod 777 '$fi'; sudo Rscript '$fi'"); 
	$fe = read3d($ofi);
	#show3($fe);
	$fe = data::comp($fe, "@alle@ = 1;");
	return $fe;
}

function cox_backwards($fe, $zeit, $event, $cov){
	$l = chr(10); $t = chr(9);
	$ofi = "/tmp/coxvalue.out";
	$fe = getmat2($fe, $zeit.",".$event.",".$cov);
	$cov = preg_replace("/,/", " + ", $cov);
	
	$f = "/tmp/tmp_cox_backwards.dat"; writefe($fe, $f, 1);
	$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
	$r .= "library(splines); library(survival);".$l;
	$r .= "c0 <- coxph(formula = Surv($zeit, $event) ~ $cov, data = x);".$l;
	$r .= "library(MASS); c1 <- stepAIC(c0, direction = 'forward'); c2 <- summary(c1);".$l;
	$r .= "c3 <- data.frame(cbind(rownames(c2\$coefficients), c2\$coefficients, c2\$conf.int))[, c(1,6,7,8,9)];".$l;
	$r .= "colnames(c3) <- c('av', 'p_cox', 'or_cox', 'lci_cox', 'uci_cox');".$l;
	$r .= "n <- data.frame(cbind(paste('n =', nrow(x)), '', '', '', '')); colnames(n) <- colnames(c3); c3 <- rbind(c3, n);".$l;
	$r .= "write.table(c3, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); ".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	
	$fe = read2d($ofi);
	$fe = selif3($fe, "@av@ !== '' ");
	$fe = function_on_fe($fe, "_" , "format2(@, '0.0000')");
	comp($fe, "@p_cox@ = pwertbold(@p_cox@);");
	comp($fe, "@or_cox@ = or_farbe(@or_cox@);");
	$fe = labelheaders($fe);
	$fe = labelcolumn0($fe);
	$fe[0][0] = "";
		
	$t = show($fe);
	$t = "<link rel = 'stylesheet' href = 'styles.css'><link rel = 'stylesheet' href = 'index.css'>".$t;
	write2($t, "/eigenes/downs/test.html");
	#write2($t, "/mnt/51/downs/test222.html");
}

function collectresults($method, $tb, $zeit, $event, $covs, $fe){
	$t = "results";	
	//myq("drop table if exists $t");
	myq("create table if not exists $t (method text, tb text, zeit text, event text, covs text, av text, p text, od text, lci text, uci text)");
	myq("delete from $t where method = '$method' and tb = '$tb' and zeit = '$zeit' and event = '$event' and covs = '$covs' ");
	for($i = 1; $i < count($fe); ++$i) {
		$av  = $fe[$i][0];
		$p   = $fe[$i][1];
		$od  = $fe[$i][2];
		$lci = $fe[$i][3];
		$uci = $fe[$i][4];		
		if ($p <> '' and $p <= 0.05) myq("insert into $t (method, tb, zeit, event, covs, av, p, od, lci, uci) values ('$method', '$tb', '$zeit', '$event', '$covs', '$av', '$p','$od', '$lci', '$uci')");
	}
}

// function mychart
function flowchart($neu, $boxes, $boxlabels, $arrows){
	$l = chr(10);
	$db = currdb();
	$ofi = ofi($db, "flowchart").".png";
	if ($neu == 1) {
		$r .= "library(shape); library(diagram);
			png(file = '$ofi', width = 960, height = 960, res = 140);
			par(mfrow = c(1, 1));
			par(mar = c(0, 0, 0, 0));
			openplotmat();
			elpos <- coordinates (pos = c($boxes));".$l;
			
			$fe = explode(",", $arrows);
			for($j = 0; $j < count($fe); ++$j){
				$f = fromto($fe[$j], "", "->");
				$t = fromto($fe[$j], "->", "");
				$r .= "treearrow(from = elpos[$f, ], to = elpos[$t, ], lwd = 4);".$l;
			}
		
			$fe = explode("|", $boxlabels); 
			$r .= "labels = vector(length = $gr);".$l;
			for($j = 1; $j <= count($fe); ++$j) $r .= "labels[$j] = '".$fe[$j - 1]."';".$l;
			$r .= "for(i in 1:6) textround (elpos[i, ], radx = 0.08, rady = 0.05, lab = labels[i]); dev.off(); rm(list = ls());".$l;
			$fi = "/tmp/r.cmd";
		$r = leadingweg($r);
		write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	$ofi2 = $ofi."__".zufallsstring(2);
	exec("rm ".$ofi."__*");
	exec("cp $ofi $ofi2");
 	$fi = fromto($ofi2, "/eigenes/www/$db/", "");
	echo "<img src='$fi' style = 'height:40%; width:40%;'/>";
}

function getval($av, $tb, $fu = "mean", $ru = 2){
	if ($fu == "mean") {
		$rs = myq("select round(avg($av),$ru),round(stddev($av),$ru) from ".$tb);
		$row = mysql_fetch_row($rs); err();
		$r = $row[0]." (SD = ".$row[1].")";
	}
	if ($fu == "count") {
		$rs = myq("select count($av) from ".$tb);
		$row = mysql_fetch_row($rs); err();
		$r = $row[0];
	}
	if ($fu == "min" or $fu == "max") {
		$r = recwert("select $fu($av) from ".$tb. " limit 1");
	}
	if ($fu == "median") {
		$db = currdb();
		$s = Rserve_connect(); 
		$e = Rserve_eval($s, "{ 
			con <- dbConnect(MySQL(), user='backuser', password='backuser99', dbname='$db', host='localhost');
			x <- dbGetQuery(con, 'select $av from $tb'); attach(x);
			y <- data.frame(median.ci($av));
			dbGetQuery(con, 'drop table if exists y');
			dbWriteTable(con, 'y', y, row.names = 0);
			}");
		Rserve_close($s);
		$f = explode(" ", recwert("select * from y"));
		$r = $f[0]." (".$f[1]." - ".$f[2].")";
	}
	return $r;
}

function getperc($av, $w, $tb, $ru = 0){
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";
	$rs = myq("select sum(if($av = '$w', 1, 0)), count($av), sum(if($av = '$w', 1, 0))/count($av) from $tb $wt not isnull($av) ");
	$row = mysql_fetch_row($rs); err();
	$r = round($row[2]*100,$ru)."% (".$row[0]." / ".$row[1].")";
	return $r;
}

function ofi($db, $fi){
	$ofi = "/eigenes/www/$db/out/$fi"; 
	$ofi = str_replace("=", "eq", $ofi);
	$ofi = str_replace(">", "ge", $ofi);
	$ofi = str_replace("<", "le", $ofi);
	$ofi = str_replace(" ", "_" , $ofi);

	$ofi = preg_replace("/[^a-z0-9\\/_\.]/", "", $ofi);
	return $ofi;
}

//function mytab
/*   uv1     uv2     uv3
     m sd n  m sd n  m sd n
av1
av2
av3
*/
//voll funktionierendes Beispiel
// $tb = "test"; zufallsdaten4(20, 2, $tb); 
// comp2("uv = round(c1 / 0.5, 0)", $tb, "int(1)");
// comp2("av = c2", $tb);
// showt($tb); 
// reptab1_neu(1, $tb." where uv in (0,1)", "^av$", "^uv$", "mean2,sd2,min2,median2,max2,count2", "0.00");
function reptab1_neu($neucalc, $tb, $av, $uv, $fu, $fo, $ti){  
	$db = currdb();
	if (instr($tb," where ")) $wt = " and "; else $wt = " where "; $wh = fromto($tb, " where ", "");
	
	$ofi = ofi($db, "reptab1_neu_".$tb."_".$av."_".$uv."_".$fu);
	$l = chr(10);

	$uv = vl4($uv, $tb); $uvf = explode(",", $uv); $uvgr = count($uvf);
	$av = vl4($av, $tb); $avf = explode(",", $av); $avgr = count($avf);
	$fuf = explode(",", $fu); $fugr = count($fuf);
	$uvst = "999,".uvstufen3alt($uv, $tb); $uvstf = explode(",", $uvst); $uvgr = count($uvstf) - 1;
	$st = prae_suff($uvst,"'f_x_", "'");
	
	$ti .= ", Mittelwerte und Standardabweichung (Werte soweit verfügbar), statistischer Test: ".($uvgr == 2 ? "Mann-Whitney-U-Test (Lehmann, 1998)" : "Rangvarianzanalyse nach Kruskal & Wallis (Lehmann, 1998)").", 
	    p-Werte <u><</u> 0.05 verweisen auf einen explorativ signifikanten Unterschied (Shift = Verschiebungsmaß der 
	    Gruppen gegeneinander inklusive 95%-Konfidenzband der Verschiebung, nur angegeben bei genau 2 Gruppen).";
	tb($ti);
	if ($neucalc == 0) {echop(read2($ofi)); return;}
	if (file_exists($ofi)) unlink ($ofi);

 	$r .= "con <- dbConnect(MySQL(), user = 'backuser', password = 'backuser99', dbname = '$db', host = 'localhost');".$l;
	$r .= "x <- dbGetQuery(con, 'select -999 as $uv, $av from $tb union all select $uv, $av from $tb'); 
		 x[] <- lapply(x, as.numeric); 
		 li <- list(x\$$uv); x <- subset(x, select = -c($uv) ); ".$l;

	for ($k = 0; $k < $fugr; ++$k){
		$f = $fuf[$k];
		$r .= "yy <- aggregate(x, li, $f);".$l;
		$st2 = preg_replace("/f_/", $f."_", $st);
		$r .= "yy <- data.frame(t(yy)); colnames(yy) <- c($st2); ".$l;
		if ($k == 0) $r .= "xx <- yy;".$l;
		if ($k  > 0) $r .= "xx <- cbind(xx, yy);".$l;
	
	}
	$r .= "rm(x);".$l;
	$r .= "r = data.frame(c('', ".prae_suff($av, "'", "'").")); colnames(r) <- c('av'); xx <- cbind(r, xx); ".$l;
	if (file_exists("/tmp/reptab1.txt")) unlink ("/tmp/reptab1.txt");	
	$r .= "write.table(xx, file = '/tmp/reptab1.txt', sep = '\\t', quote = F, row.names = FALSE); ".$l;
	
	#$fi = "/tmp/r.cmd"; write2($r, $fi); exec("R --no-save --slave -q < $fi > $fi.out"); return
	
	$r .= "x <- dbGetQuery(con, 'select $uv, $av from $tb'); x[] <- lapply(x, as.numeric); ".$l;
	for ($j = 0; $j < $avgr; ++$j){
		$a = $avf[$j]; $u = $uv;
		$r .= "options(warn=-1); library(exactRankTests);".$l;
		
		if (count($uvstf) <= 3) {
			#$r .= "w <- wilcox.exact($a ~ $u, data = x, conf.int = TRUE);".$l;
			$r .= "w <- wilcox.test($a ~ $u, data = x, conf.int = TRUE);".$l;
			$r .= "xx\$p     [xx\$av == '$a'] <- w\$p.value;".$l;
			$r .= "xx\$shift [xx\$av == '$a'] <- - w\$estimate;".$l;
			$r .= "xx\$lci   [xx\$av == '$a'] <- - w\$conf.int[2];".$l;
			$r .= "xx\$uci   [xx\$av == '$a'] <- - w\$conf.int[1];".$l;
			$r .= "rm(w);".$l;
		} else {
			$r .= "w <- kruskal.test($a ~ $u, data = x);".$l;
			$r .= "xx\$p     [xx\$av == '$a'] <- w\$p.value;".$l;
			$r .= "xx\$shift [xx\$av == '$a'] <- '-';".$l;
			$r .= "xx\$lci   [xx\$av == '$a'] <- '-';".$l;
			$r .= "xx\$uci   [xx\$av == '$a'] <- '-';".$l;
		}
	}
	//$r .= "rm(x); rm(wi);".$l;

	//reorder columns
	$stf = explode(",", $uvst);
	for ($s = 0; $s < count($stf); ++$s){
		for ($k = 0; $k < $fugr; ++$k) $vfe[] = $fuf[$k]."_x_".$stf[$s];
	}
	$o = "'av', '".implode("','", $vfe)."', 'p', 'shift', 'lci' ,'uci' ";
	$r .= "xx <- xx[c($o)];".$l;
	//delete row
	$r .= "xx = xx[-1, ];".$l;
	if (file_exists("/tmp/reptab2.txt")) unlink ("/tmp/reptab2.txt");	
	$r .= "write.table(xx, file = '/tmp/reptab2.txt', sep = '\\t', quote = F, row.names = FALSE); ".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi); exec("R --no-save --slave -q < $fi > $fi.out");
	
		
	//_____________________ tab __________________________
	$ff = "/tmp/reptab2.txt"; $fe = read2d($ff); if (file_exists($ff)) unlink ($ff);
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
		
	for ($i = 0; $i < $gr1; ++$i) $fe[$i][0] = givelabel($fe[$i][0]);
	//Feld für die Conclusions
	//writefe($fe, $otb);

	#tb(givelabel($av, "", 1).": means and standard deviations, statistical test: rank analysis of variance (Lehmann, 1998).");
	
	$t  = $l.$l."<table id = $ofi border = 0 cellspacing = 0 style='background-color:#E6E6E6;'>".$l;
	$t .= "<tr><td class = 'bb ss rr' ></td><td class = 'bb ss' colspan = $fugr></td><td class = 'bb ss cc' colspan = ".((count($uvstf) - 1) *$fugr).">".givelabel($uv)."</td><td class = 'bb ss' colspan = 4 ></td></tr>";

	//uv header
	$t .= "<tr><td class = s_></td>";
	for ($j = 0; $j < count($uvstf); ++$j){
		$u = $uvstf[$j];
		if ($u == 999) $q = "select count(1) from $tb"; else $q = "select count($uv) from $tb $wt $uv = $u";
		$n = "<br>n = ".recwert($q);
		$t .= "<td class = 's_ d_r cc' colspan = $fugr>".givelabel($uv, $uvstf[$j]).$n;
	}
	$t .= "</td><td class = s_ colspan = 4>".givelabel("tests")."</td>";
	$t .= "</tr>";
	
	//kw labels
	$le = $fuf[$fugr - 1];
	$t .= "<tr><td class = s_></td>";
	for ($j = 1; $j < $gr2; ++$j){
		$w = $fe[0][$j];
		$cl = "s_c";
		if (instr($w, $le)) $cl .= " d_r";
		$t .= "<td class = '$cl'>".givelabel(fromto($w, "", "_"))."</td>";
	}
	$t .= "</tr>";

	for ($i = 1; $i < $gr1 - 1; ++$i){
		$t .= $l."<tr>";
		for ($j = 0; $j < $gr2; ++$j){
			$w = $fe[$i][$j];
			$w0 = $fe[0][$j];
			if ($j > 0) {
				if ($w0 == "p") { if ($w <= 0.05) $w = "<b>".format2($w,"0.0000")."</b>"; else $w = format2($w, "0.0000"); }
				else if (instr($fe[0][$j], "count")) $w = $w;
				else $w = format2($w, $fo);
			}
			if ($i == 1) {
				$cl = "s_o"; if ($j > 0) $cl .= "r";
				if (instr($w0, $le)) $cl .= " d_r";
				$t .= "<td class = '$cl' >$w</td>";
			}
			else if ($i  > 1 and $i < $gr1 - 2) {
				$cl = "d_o"; if ($j > 0) $cl .= "r";
				if (instr($w0, $le)) $cl .= " d_r";
				$t .= "<td class = '$cl' >$w</td>";
			}
			else {
				$cl = "s_u2"; if ($j > 0) $cl .= "r";
				if (instr($w0, $le)) $cl .= " d_r";
				$t .= "<td class = '$cl' >$w</td>";
			}
		}
		$t .= "</tr>";
	}
	$t .= $l."</table>".$l.$l;
	write2($t, $ofi); writefe($fe, $ofi.".raw"); registertab($ofi);
	echop($t);
}

/*     gr1    gr2	    nur für Häufigkeiten
      n  %    n  %    OR  lowerCI upperCI	
a 1
  2
b 1
  2 
  3
*/
//reptab2(1, $tb, "gr", "a,b");				 //gr + a + b müssen real sein
//reptab2_neu(1, $tb, "gr", "a,b", "0 to 1|1 to 5");	 //mit Schema
function reptab2_neu($neucalc, $tb, $uv, $av, $schema = "", $ti, $texten = 1){  //Tabelle nur für Häufigkeiten (count2 + percent2) und OR, mit oder ohne Schema
	$db = currdb();
	if (instr($tb," where ")) $wt = " and "; else $wt = " where "; $wh = fromto($tb, " where ", "");

	$ofi = ofi($db, "reptab2_neu_".$tb."_".$uv."_".$av);
	$ti .= ", Häufigkeiten und Unterschiedsprüfung (n = number of cases, p = p-Wert zur Frage, ob sich die Gruppen unterscheiden, CI = confidence interval), statistischer Test: Fisher-Test, p-Werte <u><</u> 0.05 verweisen auf einen explorativ signifikanten Unterschied, 
			Odds-Ratio = Risikomaß inklusive der 95%-Vertrauensbereiche.";
	tb($ti);
	if ($neucalc == 0) {echop(read2($ofi)); return;}
	$l = chr(10);
	
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";
	$uv = vl4($uv, $tb); $uvf = explode(",", $uv); $uvgr = count($uvf);
	$av = vl4($av, $tb); $avf = explode(",", $av); $avgr = count($avf);
	$fu = "count2,percent2"; $fuf = explode(",", $fu); $fugr = count($fuf);
	$uvst = uvstufen3alt($uv, $tb); $uvst = "1 = 1,$uvst";  $uvstf = explode(",", $uvst); $uvstgr = count($uvstf);

	if ($schema !== "") {
		$scf = explode("|", $schema); $scfgr = count($scf);
		for ($j = 0; $j < $avgr; ++$j) {
			$scf[$j] = kontrolliere($scf[$j]);
			if (trim($scf[$j]) == "") $scf[$j] = uvstufen2($avf[$j], $tb);
			$scf[$j] = prae_suff($scf[$j], "'", "'");
		}
	}
	
	
	$r .= "con <- dbConnect(MySQL(), user='backuser', password='backuser99', dbname='$db', host='localhost');".$l;
	for ($j = 0; $j < count($uvstf); ++$j){
		$st = $uvstf[$j];
		if (instr($st, "1 = 1")) $gl = $st; else $gl = "$uv = $st";
		$r .= "x <- dbGetQuery(con, 'select $uv, $av from $tb $wt $gl '); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
		for ($i = 0; $i < count($avf); ++$i){
			for ($k = 0; $k < count($fuf); ++$k){
				
				$u = $uvstf[$j]; if (!is_numeric($u)) $u = 999;
				$a = $avf[$i];
				$f = $fuf[$k];
				if ($f == "percent2") $f .= ", ntot = count2(x\$".$a."), runde = 0";
				$r .= "y <- aggregate(x\$".$a.", list(x\$".$a."), $f); y <- cbind('$a', y);".$l;
				
				if (instr($f, "percent2")) $f = "percent2";
				$r .= "colnames(y) <- c('av', 'st','".$f."_".$u."');".$l;
				
				if ($k == 0) $r .= "x$u <- y;".$l;
				if ($k  > 0) $r .= "x$u <- merge(x$u, y, all = TRUE, by = c('av','st'));".$l;
				
			}
			
			//Schema hinzu
			if ($schema  == "") $r .= "sc <- data.frame(x$u\$st); colnames(sc) <- 'st'; ".$l;
			if ($schema !== "") {
				if ($scfgr == 1) $sc_ = $scf[0]; else $sc_ = $scf[$i]; 
				$r .= "sc <- data.frame(c($sc_)); colnames(sc) <- 'st';".$l;
			}
			$r .= "x$u <- merge(sc, x$u, all = TRUE, by = 'st' );".$l;

			$r .= "x$u\$lfn <- $i;".$l;
			$r .= "x$u\$av <- '$a';".$l;
			
			if ($i == 0) $r .= "z$u <- x$u;".$l;
			if ($i  > 0) $r .= "z$u <- rbind(z$u, x$u); ".$l;
		}
		
		if ($j == 0) $r .= "zz <- z$u;".$l;
		if ($j  > 0) $r .= "zz <- merge(zz, z$u, all = TRUE, by = c('av', 'st'));".$l;
		$r .= "rm(x);".$l;
	}
	if (file_exists("/tmp/reptab1.txt")) unlink ("/tmp/reptab1.txt");
	$r .= "zz\$lfn.y[zz\$lfn.y == NA] <- 9999;".$l;
	$r .= "zz <- zz[order(zz\$lfn.x, zz\$st), ]; zz <- zz[,!(names(zz) %in% c('lfn', 'lfn.x', 'lfn.y') )];".$l;
	$r .= "write.table(zz, file = '/tmp/reptab1.txt', sep = '\\t', quote = F, row.names = FALSE);".$l;

	$ugr = count(explode(",", uvstufen3alt($uv, $tb)));
	$z = 1;
	for ($j = 0; $j < $avgr; ++$j){
		if ($j == 0) $r .= "zz <- transform(zz, p = NA, od = NA, odl = NA, odu = NA);".$l;
		$avst = explode(",", uvstufen3alt($avf[$j], $tb));
		$agr = count($avst);
		$z = $z + $agr;
		$u = $uvf[0];
		$a = $avf[$j];
		$r .= "x <- dbGetQuery(con, 'select $uv, $a from $tb'); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
		if ($agr == 2 and $ugr == 2) {
			$r .= "f <- fisher.test(x\$".$u.", x\$".$a.", or = 1); ";
			$r .= "p <- sprintf('%.4f', f\$p.value);";
			$r .= "od <- sprintf('%.4f', f[[3]]);"; 
			$r .= "odl <- sprintf('%.4f', f[[2]][[1]]);";
			$r .= "odu <- sprintf('%.4f', f[[2]][[2]]);";
			
			$b  = "zz\$av == '$a' & zz\$st == '".$avst[0]."'";
			$r .= "zz\$p  [$b] <- p;".$l;
			$r .= "zz\$od [$b] <- od;".$l;
			$r .= "zz\$odl[$b] <- odl;".$l;
			$r .= "zz\$odu[$b] <- odu;".$l;
		} elseif ($agr >= 2 and $ugr >= 2)  {
			$r .= "chi <- chisq.test(x\$".$u.", x\$".$a."); ".$l;
			#$r .= "p <- paste('<tg id = pw$j>', chi\$p.value, '</tg>');";
			$r .= "zz\$p[zz\$av == '$a'] <- p;".$l;
		}
	}
	if (file_exists("/tmp/reptab2.txt")) unlink ("/tmp/reptab2.txt");
	$r .= "rm(x); write.table(zz, file = '/tmp/reptab2.txt', sep = '\\t', quote = F, row.names = FALSE); ".$l;
	$fi = "/tmp/r2.cmd"; write2($r, $fi); 

	$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	//exec("R --no-save --slave -q < $fi > $fi.out");
	
	//________________________________ tab ____________________________________
	$fe = read2d("/tmp/reptab2.txt");
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	
	for ($i = 0; $i < $gr1; ++$i){
		$a = $fe[$i][0];
		$c = $fe[$i][1];
		$fe[$i][0] = givelabel($a);
		$fe[$i][1] = givelabel($a, $c);
	}
	
	//Doppelte weg
	for ($i = $gr1 - 1; $i > 0; $i--){
		if ($fe[$i][0] == $fe[$i - 1][0]) $fe[$i][0] = "";
		#for ($j = 0; $j < $gr2; ++$j){
			#$w0 = $fe[0][$j];
			#if ($w0 == "p" or $w0 == "od" or $w0 == "odl" or trim($w0) == "odu") $gef = 1; else $gef = 0;
			#if ($gef == 1 and $fe[$i][$j] == $fe[$i - 1][$j]) $fe[$i][$j] = "";
		#}
	}

	$z = -1; for ($i = 1; $i < $gr1; ++$i) if($fe[$i][0] !== "") {++$z; $fe[$i][0] = "<tg id = la$z>".$fe[$i][0]."</tg>";}

	$na = "tb_".zufallsstring(5);
	$tabname = "id = ".$na; #registertab($na);	
	$t  = $l."<table $tabname border = 0 cellspacing = 0 style='background-color:#E6E6E6;'>".$l;
	//uv header
	$uvst = uvstufen3alt($uv, $tb); $uvst = "999,$uvst";  $uvstf = explode(",", $uvst); $uvstgr = count($uvstf);
	$t .= "<tr><td class = s_o></td><td class = s_o></td>";
	for ($j = 0; $j < count($uvstf); ++$j){
		$u = $uvstf[$j]; 
		if ($u == 999) $q = "select count(1) from $tb"; else $q = "select count($uv) from $tb $wt $uv = $u";
		$n = "<br>n = ".recwert($q);
		$t .= "<td class = 's_o d_r cc' colspan = $fugr>".givelabel($uv, $uvstf[$j]).$n;
	}
	$t .= "</td><td class = s_o colspan = 5>tests</td>";
	$t .= "</tr>";
	
	//kw labels
	$le = $fuf[$fugr - 1];
	$t .= "<tr><td class = s_></td>";
	$fe[0][0] = ""; $fe[0][1] = "";

	for ($j = 1; $j < $gr2; ++$j){
		$w = $fe[0][$j];
		if (instr($w, $le) == 0) $cl = "s_"; else $cl = "d_r";
		if ($j > 1 and $j < $gr2 - 4) $cl .= " s_r";
		$t .= "<td class = '$cl'>".givelabel(fromto(trim($w), "", "_"))."</td>";
	}
	$t .= "</tr>";
	
	for ($i = 1; $i < $gr1 - 1; ++$i){
		$t .= $l."<tr>";
		for ($j = 0; $j < $gr2; ++$j){
			$id = $fe[0][$j].($i);
			$w = $fe[$i][$j]; if (trim($w) == "NA") $w = "";
			$w0 = $fe[0][$j]; $w1 = $w0.$i;
			if ($j > 0) {
				if ($w0 == "p")  { $w = format3("tg", $w, "0.0000"); if (strip_tags($w) < 0.05) $w = "<b>$w</b>"; }
				if (instr($w0, "od")) $w = format3("tg", $w, "0.0000");
			}
			
			$s1 = $fe[$i][0];
			
			if (trim($s1) !=="") $cl = "s_o"; else if ($j > 0 and $j < $gr2 - 4) $cl = "d_o"; else $cl = "s_";
			if ($j > 1 and $j < $gr2 - 4) $cl .= "r";
			
			if (instr($w0, $le) == 1) $cl .= " d_r";
			
			if ($i == 1) $t .= "<td id = '$w1' class = '$cl' >$w</td>";
			else if ($i  > 1 and $i < $gr1 - 2) $t .= "<td id = '$w1' class = '$cl' >$w</td>";
			else {
				if ($j > 0 and $j < $gr2 - 4) $cl = "s_u2"; else $cl = "s_u1";
				if ($j > 1 and $j < $gr2 - 4) $cl .= "r";
				if (instr($w0, $le) == 1) $cl .= " d_r";
				$t .= "<td id = '$w1' class = '$cl' >$w</td>";
			}
		}
		$t .= "</tr>";
	}
	$t .= $l."</table>";
	write2($t, $ofi); writefe($fe, $ofi.".raw"); registertab($ofi);
	echop($t);
	#if ($texten == 1) ortext($ofi);
}

function ortext_neu($ofi, $lg = "en", $fo = "0.00", $pfo = "0.0000"){
	$db = currdb();
	
	$fe = read2d($ofi.".raw");
	$fe = selif3($fe, "@od@ !== ''");
	$gr1 = count($fe);
	show($fe); return;

	for ($j = 0; $j < $gr2; ++$j) {
		$w0 = $fe[0][$j];
		if ($w0 == "p" ) $p_sp = $j;
		if ($w0 == "od") $p_od = $j;
	}
	//Leerzeichen und NA weg
	for ($i = 1; $i < $gr1; ++$i) for ($j = 0; $j < $gr2; ++$j){$w = trim($fe[$i][$j]); if ($w == "NA") $w = ""; $fe[$i][$j] = trim($w);}

	for ($i = 1; $i < $gr1; ++$i){
		$a   = $fe[$i][0];
		$p   = $fe[$i][$p_sp];
		$od  = $fe[$i][$p_od];
		$odl = $fe[$i][$p_od + 1];
		$odu = $fe[$i][$p_od + 2];
		if ($p <= 0.05 and $p !== "" and $odl !== "" and $odu !== "") {
			if ($odl > 1) $r_inc[] = $a." (".pwert($p, $pfo).", OR = ".format2($od, $fo).")";
			if ($odu < 1) $r_dec[] = $a." (".pwert($p, $pfo).", OR = ".format2($od, $fo).")";
		}
		if ($p <= 0.05 and $p !== "" and $odl  == "" and $odu  == "") {
			$r_tot[] = $a." (".pwert($p, $fo).")";
		}
	}
	
	if ($lg == "en") $sp = array(" and for ", ", for ", ", and finally"    , "Risk increases were found for "             , "Risk reductions were found for "              , "Differences were found for "            , "No differences were found.");
	if ($lg == "de") $sp = array(" und für ", ", für ", ", und schließlich", "Risikoerhöhungen fanden sich mit Blick auf ", "Risiko-Minderungen fanden sich mit Blick auf ", "Unterschiede fanden sich mit Blick auf ", "Es fanden sich keine Unterschiede");

	if (trim($r_tot[0]) !== "") {  // wenn nur p-Wert aber kein OR
		if (count($r_tot) == 2) $tx = $sp[0];
		if (count($r_tot) >= 3) {$tx = $sp[1]; $r_tot[count($r_tot) - 2] .= $sp[2]; }
		$t .= $sp[5].implode($tx, $r_tot).". ";
	}
	if (trim($r_inc[0]) !== "") {
		if (count($r_inc) == 2) $tx = $sp[0];
		if (count($r_inc) >= 3) {$tx = $sp[1]; $r_inc[count($r_inc) - 2] .= $sp[2]; }
		$t .= $sp[3].implode($tx, $r_inc).". ";
	}
	if (trim($r_dec[0]) !== "") {
		if (count($r_dec) == 2) $tx = $sp[0];
		if (count($r_dec) >= 3) {$tx = $sp[1]; $r_dec[count($r_dec) - 2] .= $sp[2]; }
		$t .= $sp[4].implode($tx, $r_dec).". ";
	}
	if ($t == "") $t = $sp[6];

	return $t;
}


function meantext($uv, $av, $lg = "en", $fo = "0.00", $pfo = "0.0000"){
	$db = currdb();	
	$otb = "/eigenes/www/$db/out/reptab1_neu_".$uv."_with_".$av."_fe"; $otb = preg_replace("/[^a-z0-9\\/_]/", "", $otb);
	if (!file_exists($otb)) return;
	$fe = read2d($otb);
	
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	
	for ($j = 0; $j < $gr2; ++$j) if ($fe[0][$j] == "p") $p_sp = $j;
	
	//Leerzeichen und NA weg
	for ($i = 1; $i < $gr1; ++$i) for ($j = 0; $j < $gr2; ++$j){$w = trim($fe[$i][$j]); if ($w == "NA") $w = ""; $fe[$i][$j] = $w;}
	
	for ($i = 1; $i < $gr1; ++$i){
		$a = $fe[$i][0];
		$p = $fe[$i][$p_sp];
		if ($p <= 0.05 and $p !== "") $d[] = $a." (".pwert($p, $fo).")";
	}
	
	if ($lg == "en") $sp = array(" and for ", ", for ", ", and finally"    , "Differences were found for ");
	if ($lg == "de") $sp = array(" und für ", ", für ", ", und schließlich", "Unterschiede fanden sich mit Blick auf ");

	if (trim($d[0]) !== "") {
		if (count($d) == 2) $tx = $sp[0];
		if (count($d) >= 3) {$tx = $sp[1]; $d[count($d) - 2] .= $sp[2]; }
		$t .= $sp[3].implode($tx, $d).". ";
	}

	return $t;
}

function regrtext($uv, $av, $lg = "en", $fo = "0.00", $pfo = "0.0000"){
	$db = currdb();
	$otb = "/eigenes/www/$db/out/regr_".$av."_from_".$uv."_fe"; $otb = preg_replace("/[^a-z0-9\\/_]/", "", $otb);	
	if (!file_exists($otb)) return;
	
	$fe = read2d($otb);
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	
	//show($fe); return;
	
	for ($j = 0; $j < $gr2; ++$j) if ($fe[0][$j] == "p") $p_sp = $j;
	
	//Leerzeichen und NA weg
	for ($i = 1; $i < $gr1; ++$i) for ($j = 0; $j < $gr2; ++$j){$w = trim($fe[$i][$j]); if ($w == "NA") $w = ""; $fe[$i][$j] = $w;}
	
	for ($i = 1; $i < $gr1; ++$i){
		$a = $fe[$i][0];
		$p = $fe[$i][$p_sp];
		if ($p <= 0.05 and $p !== "" and instr($a, "intercept") ==0 ) $d[] = givelabel($a)." (".pwert($p, $pfo).")";
	}
	
	if ($lg == "en") $sp = array(" and ", ", ", " and, finally", "Significant predictors were ");
	if ($lg == "de") $sp = array(" und ", ", ", " und schließlich", "Signifikante Prädiktoren waren ");

	if (trim($d[0]) !== "") {
		if (count($d) == 2) $tx = $sp[0];
		if (count($d) >= 3) {$tx = $sp[1]; $d[count($d) - 2] .= $sp[2]; }
		$t .= $sp[3].implode($tx, $d).". ";
	}
	return $t;
}

function uvseq0($fe, $uv, $schema = "", $colwidth = "140,110"){
	$schema = kontrolliere($schema); $sf = explode(",", $schema);
	$uvf = vl($fe[0], $uv);
	
	for ($j = 0; $j < count($uvf); ++$j) $t[] = uvstufen($fe, $uvf[$j]).",";
	
	$t = implode(",", $t);
	$tfe = array_filter(explode(",", $t));
	$tfe = array_unique($tfe);
	sort($tfe); 
	
	$fe0[0][0] = "gr";
	if ($schema == "") for ($j = 0; $j < count($tfe); ++$j) $fe0[$j + 1][0] = $tfe[$j]; else for ($j = 0; $j < count($sf); ++$j) $fe0[$j + 1][0] = $sf[$j];
	$fe0[count($fe0)][0] = "Summe";
	$fe0[count($fe0)][0] = ".";
	$fe0[count($fe0)][0] = "Gesamt";

	for ($j = 0; $j < count($uvf); ++$j){
		$fe2 = freq1b($fe, "^".$uvf[$j]."$", 1);
		$fe2[0][0] = "gr";
		$fe0 = merge0($fe0, $fe2); 
	}
	$fe0[0][0] = $uvf[0];
	for ($i = 1; $i < count($fe0); ++$i) $fe0[$i][0] = givelabel($fe0[0][0], $fe0[$i][0]);
	
	$g = givelabel($uvf[0], "", 1); $fe0[0][0] = $g;
	$cl = "class = s_o";
	for ($j = 0; $j < count($uvf); ++$j) $h .= "<td colspan = 2 align = center valign = middle $cl >".givelabel($uvf[$j])."</td>";
	tb($g." - Häufigkeiten (n = number of cases, '.' = keine Angabe ).");
	showneu($fe0, "1,2",  $colwidth , "^0$:.:s_o s_u1,^last$:.:s_u1,[2-9]|1[0-9]:.:d_o", "<tr><td $cl ></td>$h</tr>");
}

function uvseq($neucalc, $tb, $uv, $fu, $schema = ""){ //uv-Sequenz, d.h. eine Folge von uv, einfach horizontal aneinander gereiht
	$db = currdb();
	$ofi = "/eigenes/www/$db/out/uvseq_".$uv; $ofi = preg_replace("/[^a-z0-9\\/_]/", "", $ofi);
	if ($neucalc == 0) {echop(read2($ofi)); return;}
	
	$otb = "tmp";
	$l = chr(10);
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";
	$oo = fromto($tb, "", " where ")."_joined";
	
	$uv = vl3($uv, $tb); $uvf = explode(",", $uv); $uvgr = count($uvf);
	$fuf = explode(",", $fu); $fugr = count($fuf);
	
	dropt2("^".$otb);
	$r .= "con <- dbConnect(MySQL(), user='backuser', password='backuser99', dbname='$db', host='localhost');".$l;
	$r .= "x <- dbGetQuery(con, 'select $uv from $tb'); attach(x);".$l;
	if ($schema !== "") $r .= "schema <- data.frame(c(".kontrolliere($schema).")); colnames(schema) <- c('st');".$l;
	$z = 0;
	for ($j = 0; $j < count($uvf); ++$j){
		for ($k = 0; $k < count($fuf); ++$k){
			$u = $uvf[$j];
			$f = $fuf[$k];
			$o = $otb."_uv_".$u."_".$f;
			if ($f == "percent2") $f .= ", ntot = count2($u), runde = 1";
			++$z; $c[$z] = $fuf[$k]."_x_".$u;
			$r .= "y$k <- aggregate($u, list($u), $f); colnames(y$k) <- c('st','$c[$z]');".$l;

			if ($k == 0) {if ($schema !== "") $r .= "y$u <- merge(schema, y$k, all = TRUE, by = 'st');".$l; else $r .= "y$u <- y$k;".$l;}
			if ($k > 0) $r .= "y$u <- merge(y$u, y$k, all = TRUE, by = 'st');".$l;
		}
		if ($j == 0) $r .= "y <- y".$uvf[0].";".$l;
		if ($j >  0) $r .= "y <- merge(y, y".$uvf[$j].", all = TRUE, by = 'st');".$l;
		$r .= "write.table(y, file = '/tmp/uvseq.txt', sep = '\\t', quote = F, row.names = FALSE); ".$l;			
	
	}
	$fi = "/tmp/r.cmd"; write2($r, $fi); //$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	exec("R --no-save --slave -q < $fi > $fi.out"); //$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);

	$tt = "tmpuvseq";
	myq("drop table if exists $tt");
	myq("create table $tt (st text, ".implode(" text, ", $c)." text)");
	myq("load data local infile '/tmp/uvseq.txt' into table $tt fields terminated by '\\t' lines terminated by '\\n' ignore 1 lines");

	//_____________________ tab __________________________
	$v = implode(", ", $c);
	$fe = readmysql("select st, $v from $tt");
	
	$wi = "border-width: 1px"; $pa = "padding-left:5px; padding-right:5px";
	
	$s_   = "style='$wi; $pa;'";
	$s_o  = "style='$wi; border-top-style:    solid; $pa;'";
	$s_o2 = "style='$wi; border-top-style:    solid; $pa; text-align: right;'";
	$s_o2 = "style='$wi; border-top-style:    solid; $pa; text-align: center;'";
	$s_u1 = "style='$wi; border-bottom-style: solid; $pa;'";
	$s_u2 = "style='$wi; border-bottom-style: solid; border-top-style: dotted; $pa;'";
	
	$d_o  = "style='$wi; border-top-style:    dotted; $pa;'";
	$d_u  = "style='$wi; border-bottom-style: dotted; $pa;'";
	$d_r  = "style='$wi; border-right-style:  dotted; $pa;'";
	
	$t  = "<table border = 0 cellspacing = 0 style='background-color:#E6E6E6;'>".$l;
	for ($j = 0; $j < count($uvf); ++$j) $m[$j] = "<td colspan = $fugr $s_o2 >".givelabel($uvf[$j])."</td>"; 
	$t .= "<tr><td $s_o></td>".implode("", $m)."</td></tr>".$l;
	
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	$fe[0][0] = " ";
	for ($i = 0; $i < $gr1; ++$i){
		$t .= "<tr>";
		for ($j = 0; $j < $gr2; ++$j){
			$w = $fe[$i][$j];
			if ($w == "NA") $w = "";
			if ($j == 0) $w = givelabel($uvf[0], $w, 0);
			if ($i == 0)  $t .= "<td style='text-align: right'>".givelabel(fromto($w, "", "_x_"))."</td>";
			else if ($i < $gr1 - 1) {
				if ($i == 1) $li = $s_o; else $li = $d_o;
				if ($j > 0) $li = str_replace("5px;", "5px; text-align: right;", $li);
				$t .= "<td $li>$w</td>";
			} else {
				$li = $s_u2;
				if ($j > 0) $li = str_replace("5px;", "5px; text-align: right;", $li);
				$t .= "<td $li>$w</td>";
			}
		}
		$t .= "</tr>";
	}
	$t .= "</table>";
	

	write2($t, $ofi);
	echop($t);
	return;
}

//function mytab
/*   m  sd  n
av1
av2
av3 
*/
//_________ voll funktionierendes Beispiel ______________
// $fe = getrnd(10, 5);
// $tb = "tmp"; push($fe, "tmp");
// avstapel(1, $tb, "^c[1-3]$", "mean2,sd2,min2,median2,max2,count2");
function avstapel($neu, $tb, $av, $fu, $fo = "0.00", $order = ""){ //Folge von av, einfach nach unten gestapelt, $order z.B. order by mean desc
	$db = currdb();
	$l = chr(10);
	$ofi = ofi($db, "avstapel_".$tb."_".$av."_".$fu);

	if ($neu == 1) {
		$av = vl4($av, $tb); $avf = explode(",", $av); $avgr = count($avf);
		$fuf = explode(",", $fu); $fugr = count($fuf);
		
		$r .= "con <- dbConnect(MySQL(), user = 'backuser', password = 'backuser99', dbname = '$db', host = 'localhost');".$l;
		$r .= "library(plyr); library(PropCIs); ".$l;
		$r .= "x <- dbGetQuery(con, 'select 1 as alle, $av from $tb'); x[] <- lapply(x, as.numeric);".$l;
		
		for ($j = 0; $j < $avgr; ++$j){
			$a = $avf[$j];
			$fs1 = "'".implode("', '", $fuf)."'";
			$fs2 = implode("($a), ", $fuf)."($a)";
		
			$r .= "x2 <- x[ ,c('$a', 'alle')]; x2 <- subset(x2, complete.cases(x2));".$l;
			$r .= "y = ddply(x2, ~ x\$alle$u, summarise, $fs2); y[1,1] <- '$a'; colnames(y) <- c('av', $fs1);".$l;
			
			if ($j == 0) $r .= "y2 <- y; ".$l;
			if ($j >= 1) $r .= "y2 <- rbind(y2, y); ".$l;
						
		}
		$r .= "write.table(y2, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); ".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	$fe = read2d($ofi);
 	$fe = selif3($fe, "@av@ <> ''");
 	comp($fe, "@av@ = givelabel(@av@);"); 
	$fe = function_on_fe($fe, "^pm|^m|^sd" , "format2(@, '0.0')"); 	
	$fe = labelheaders($fe);  $fe[0][0] = " ";
	tb("Deskriptive Statistiken (n = number of cases, d.h. Stichprobengröße, p = explor. Signifikanztest).");
	showneu($fe);
}

//function mytab
//uvstapelbin_by_1avbin(1, $tb, "tod", "gr|sex|agek");
/*      %tod  sum   n   ci cil ciu  diff cil ciu
gr   1
     2
sex  1
     2
agek 1
     2  */
function uvstapelbin_by_1avbin($neu, $tb, $av, $uv){ //mehrere uv, nur 1 av
	$db = currdb();
	$l = chr(10);
	$ofi = ofi($db, "binaerstapel_by_uv".$av."_".$uv."_".$fu);
	if ($neu == 1) {
		$uv = vl4($uv, $tb); $uvf = explode(",", $uv); $uvgr = count($uvf);
		$av = vl4($av, $tb);
		$fu = "sum2,count2,prop.ci"; $fuf = explode(",", $fu); $fugr = count($fuf); 
		
		$fs1 = "'".implode("', '", $fuf)."'";
		$fs2 = implode("($av), ", $fuf)."($av)";
		
		$r .= "con <- dbConnect(MySQL(), user = 'backuser', password = 'backuser99', dbname = '$db', host = 'localhost');".$l;
		$r .= "library(plyr); library(PropCIs); ".$l;
		$r .= "x <- dbGetQuery(con, 'select $uv, $av from $tb');".$l;
		
		for ($j = 0; $j < $uvgr; ++$j){
			$u = $uvf[$j];
			$r .= "x2 <- x[ ,c('$av','$u')]; x2 <- subset(x2, complete.cases(x2));".$l;
			$r .= "y = ddply(x2, ~ $u, summarise, $fs2); colnames(y) <- c('gr', $fs1); y['uv'] <- '$u'; ".$l;
			$r .= "if (nrow(y) == 2) {
					s1 = y[2,2]; n1 = y[2,3]; s2 = y[1,2]; n2 = y[1,3]; d <- diffscoreci(s1, n1, s2, n2, 0.95);
					y['diff'] <- paste( s1/n1 - s2/n2, ';', d[[1]][1], ';', d[[1]][2] ); 
				 } else {y['diff'] <- '-';}".$l;
			if ($j == 0) $r .= "y2 <- y;".$l; else $r .= "y2 <- rbind(y2, y);".$l;
		}
		$r .= "write.table(y2, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); ".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	$fe = read2d($ofi);
 	$fe = selif3($fe, "@uv@ <> ''");
 	comp($fe, "\$a = explode(';', @prop.ci@); @prop@ = \$a[0]; @prop_lci@ = \$a[1]; @prop_uci@ = \$a[2];");
 	comp($fe, "\$a = explode(';', @diff@); @di@ = \$a[0]; @di_lci@ = \$a[1]; @di_uci@ = \$a[2];");
 	comp($fe, "@gr@ = givelabel(@uv@, @gr@);"); 
 	comp($fe, "@uv@ = givelabel(@uv@);");
 		
 	$fe = spalteloeschen2($fe, "prop.ci$|diff$");
 	$fe = function_on_fe($fe, "^prop|^di" , "format2(@ * 100, '0.0')");
 	$fe = getmat2($fe, "uv,gr,sum2,count2,^prop,^di");
	$fe = doppelte_zeilen_leer($fe, "^uv$|^di");
	$fe = labelheaders($fe);  $fe[0][0] = ""; $fe[0][1] = "";
	
	show($fe);
}

//$fe = doppelte_zeilen_leer($fe, "^uv$|^di"); //setzt Doppelnennungen in diesen Spalten auf Leer
function doppelte_zeilen_leer($fe, $vars){
	$c = getcols($fe[0], $vars); $cf = explode(",", $c); $cfz = count($cf);
	for ($i = 2; $i < count($fe); ++$i){
		for ($j = 0; $j < $cfz; ++$j){
			$s = $cf[$j];
			if ($fe[$i - 1][$s] == $fe[$i][$s] ) $fe[$i][$s] = "";
		}
 	}
 	return $fe;
}

//function mytab
/*    m sd n shift cil ciu
a  1
   2
b  1
   2
//*/
		
function uvstapel($neucalc, $tb, $uv, $av, $fu, $fo = "0.00"){ //Folge von uv, nach unten gestapelt (mehrere uv, aber nur 1 av angeben)
	$ti = 0;
	$db = currdb();
	$ofi = "/eigenes/www/$db/out/uvstapel_".$uv."_with_".$av; $ofi = preg_replace("/[^a-z0-9\\/_]/", "", $ofi);	
	if ($neucalc == 0) { echop(read2($ofi)); return;}
	
	$l = chr(10);
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";
	dropt2("^tmp_");
	$oo = fromto($tb, "", " where ")."_joined";
	$db = currdb();
	
	$uv = vl3($uv, $tb); $uvf = explode(",", $uv); $uvgr = count($uvf);
	$av = vl3($av, $tb); $avf = explode(",", $av); $avgr = count($avf);
	$fuf = explode(",", $fu); $fugr = count($fuf);
	
	$time_start = microtime(true); $time_start0 = microtime(true);
	$r .= "con <- dbConnect(MySQL(), user='backuser', password='backuser99', dbname='$db', host='localhost');".$l;
	$r .= "x <- dbGetQuery(con, 'select $uv, $av from $db.$tb'); attach(x);".$l;
	for ($j = 0; $j < count($uvf); ++$j){
		$u = $uvf[$j];
		$a = $avf[0];
		for ($k = 0; $k < count($fuf); ++$k){
			$f = $fuf[$k];
			$o = "tmp_".$u."_".$a."_".$f;
			$r .= "$o <- aggregate($a, list($u), $f); $o <- cbind('$u', $o); colnames($o) <- c('uv', 'gr','$f'); $o <- data.frame($o); ".$l;
			$o2 = "tmp_".$u."_".$a;
			
			if ($k == 0) $r .= "$o2 <- $o; ".$l;
			if ($k >= 1) $r .= "$o2 <- merge($o2, $o, by = c('uv','gr')); ".$l;
		}
		$r .= "$o2 <- cbind(rownames($o2), $o2); ".$l;
		$r .= "colnames($o2)[1] <- 'row_names'; ".$l;
		
		$r .= "l <- data.frame(aggregate($a, list($u), 'count2')); ".$l;
		$r .= "if (nrow(l) == 2) {
				w <- wilcox.test($a ~ $u, data = x, conf.int = TRUE); 
				$o2 <- cbind($o2, w\$p.value, w\$estimate, w\$conf.int[1], w\$conf.int[2]); 
			} else if (nrow(l) < 2) {
				w <- c('', '', '', '')
				$o2 <- cbind($o2, w[1], w[2], w[3], w[4]); 
				names($o2)[names($o2) == 'w[1]'] = 'w\$p.value';
				names($o2)[names($o2) == 'w[2]'] = 'w\$estimate';
				names($o2)[names($o2) == 'w[3]'] = 'w\$conf.int[1]';
				names($o2)[names($o2) == 'w[4]'] = 'w\$conf.int[2]';
			} else if (nrow(l) > 2) {
				#kruskal.test(y~A)
			}".$l;
		
		$r .= "write.table($o2, file = '/tmp/$o2', sep = '\\t', quote = F); ".$l;
		
		if ($j == 0) $r .= "tmpadd <- $o2; ".$l;
		if ($j >= 1) $r .= "tmpadd <- rbind(tmpadd, $o2); ".$l;
	}
	$r .= "write.table(tmpadd, file = '/tmp/tmpadd', sep = '\\t', quote = F); ".$l;

	$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	if ($ti == 1) {$time_end = microtime(true); echo "r: +".round($time_end - $time_start,1)." s"; $time_start = microtime(true); br(1);}
	//return;
	
	myq("drop table if exists tmpadd");
	myq("create table tmpadd (lfn int, row_names int, uv text, gr text, ".implode(" text, ", $fuf)." text, pwert text, hl text, hll text, hlu text) engine = myisam");
	myq("load data local infile '/tmp/tmpadd' into table tmpadd fields terminated by '\\t' lines terminated by '\\n' ignore 1 lines");	

	if ($ti == 1) {$time_end = microtime(true); echo "load: +".round($time_end - $time_start,1)." s"; $time_start = microtime(true); br(1);}
	//return;
	
	myq("alter table tmpadd modify gr text");
	$rs = myq("select uv,gr from tmpadd");
	while($row = mysql_fetch_row($rs)){
		$u  = $row[0];
		$c  = $row[1];
		$lb = givelabel($u, $c);
		myq("update tmpadd set gr = '$lb' where uv = '$u' and gr = $c ");
	}
	myq("update tmpadd set uv = '', pwert = '', hl = '', hll = '', hlu = '' where row_names > 1");
	if ($ti == 1) {$time_end = microtime(true); echo "alter: +".round($time_end - $time_start,1)." s"; $time_start = microtime(true); br(1);}
	//return;
	
	//_____________________ tab __________________________

	$fu .= ", pwert, hl, hll, hlu"; $fuf = explode(",", $fu); $fugr = count($fuf);
	$fe = readmysql("select uv, gr, $fu from tmpadd");

	$t  = "<table border = 0 cellspacing = 0 style='background-color:#E6E6E6;'>".$l;
	$t .= "<tr><td class = 's_o' ></td><td class = 's_o' ></td><td align=center colspan = ".($fugr + 3)." class = s_o>".givelabel($avf[0])."</td> </tr>".$l;
	
	$gr1 = count($fe);
	$gr2 = count($fe[0]);
	for ($i = 0; $i < $gr1; ++$i){
		$t .= "<tr>";
		for ($j = 0; $j < $gr2; ++$j){
			$k = $fe[0][$j];
			$w = $fe[$i][$j];
			//kw-labels
			if ($i == 0)  {
				if ($j <= 1) $w = ""; else $w = givelabel($w);
				$t .= "<td class = 's_r' >$w</td>";
			//Mittelteil
			} else {
				if ($w !== "" and $j == 0) $w = givelabel($w);
				if ($j >= 2 ){
					if ($k <> "pwert" and $k <> "count2") $w = format2($w, $fo);
					if ($k == "pwert") {$w = format2($w, "0.0000"); if ($w <= 0.05 ) $w = "<b>$w</b>"; }
				}
				if (trim($fe[$i][0]) !== "") $cl = "s_o"; else {
					if ($j == 0 or $j >= $gr2 - 4) $cl = "s_"; else $cl = "d_o";
				}
				//letzte Zeile
				if ($i == $gr1 - 1) $cl .= " s_u1";
				if ($j > 1) $cl .= "r";
				$t .= "<td class = '$cl' >$w</td>";
			}
			
		}
		$t .= "</tr>";
	}
	$t .= "</table>";

	write2($t, $ofi);
	echop($t);
	if ($ti == 1) {$time_end = microtime(true); echo "tab: +".round($time_end - $time_start,1)." s"; $time_start = microtime(true); br(1);
		$time_end = microtime(true); echo "gesamt: ".round($time_end - $time_start0,1)." s";
	}
}

//function mytab
/*    av1                 av2                 av3
      m n shift cil ciu | m n shift cil ciu | m n shift cil ciu
a  1
   2
b  1
   2
c  1
   2
uvstapel_neu(1, $tb, "uv1 to uv3", "av[1-3]$", "mean2,count2", "0", 0) //tests 0 = ohne p, 1 = p, 2 = p+shift+cil+ciu
uvstapel_neu(1, $tb, "gender", "age", "mean2,sd2,min2,median2,max2,count2", "0", 1);
*/
function uvstapel_neu($neucalc, $tb, $uv, $av, $fu, $fo = "0.00", $tests = 1){ //Folge von uv, nach unten gestapelt (mehrere uv, aber auch mehrere av angeben)
	$db = currdb();
	$ofi = "/eigenes/www/$db/out/uvstapel_neu_".$uv."_with_".$av; $ofi = preg_replace("/[^a-z0-9\\/_]/", "", $ofi);
	if ($neucalc == 0) { echop(read2($ofi)); return;}
	$l = chr(10);
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";
	
	$uv = vl3($uv, $tb); $uvf = explode(",", $uv); $uvgr = count($uvf);
	$av = vl3($av, $tb); $avf = explode(",", $av); $avgr = count($avf);
	$fuf = explode(",", $fu); $fugr = count($fuf);
	
	//*
	$r .= "con <- dbConnect(MySQL(), user='backuser', password='backuser99', dbname='$db', host='localhost');".$l;
	$r .= "x <- dbGetQuery(con, 'select $uv, $av from $db.$tb'); attach(x);".$l;
	
	for ($i = 0; $i < count($avf); ++$i){
		for ($j = 0; $j < count($uvf); ++$j){
			$u = $uvf[$j];
			$a = $avf[$i];
			for ($k = 0; $k < count($fuf); ++$k){
				$f = $fuf[$k];
				$o = "tmp_".$u."_".$a."_".$f;
				$r .= "$o <- aggregate($a, list($u), $f); $o <- cbind('$u', $o); colnames($o) <- c('uv', 'gr','$f'); $o <- data.frame($o); ".$l;
				$o2 = "tmp_".$u."_".$a;
				
				if ($k == 0) $r .= "$o2 <- $o; ".$l;
				if ($k >= 1) $r .= "$o2 <- merge($o2, $o, by = c('uv','gr')); ".$l;
			}
			
			if ($tests >= 1){
				$r .= "l <- data.frame(aggregate($a, list($u), 'count2')); ".$l;
				$r .= "if (nrow(l) == 2) {
						w <- wilcox.test($a ~ $u, data = x, conf.int = TRUE);
						$o2 <- cbind($o2, w\$p.value, w\$estimate, w\$conf.int[1], w\$conf.int[2]);
					} else if (nrow(l) < 2) {
						w <- c(NA, NA, NA, NA);
						$o2 <- cbind($o2, w[1], w[2], w[3], w[4]);
					} else if (nrow(l) > 2) {
						w <- c(NA, NA, NA, NA);
						k <- kruskal.test(x\$".$a." ~ x\$".$u.");
						$o2 <- cbind($o2, k\$p.value, w[2], w[3], w[4]);
					}".$l;
				$r .= "gr2 <- ncol($o2); ".$l;
				$r .= "colnames($o2)[gr2 - 3] <- 'p'; ".$l;
				$r .= "colnames($o2)[gr2 - 2] <- 'shift'; ".$l;
				$r .= "colnames($o2)[gr2 - 1] <- 'cil'; ".$l;
				$r .= "colnames($o2)[gr2 - 0] <- 'ciu'; ".$l;
			}
			//$r .= "write.table($o2, file = '/tmp/$o2', sep = '\\t', quote = F, row.names = FALSE); ".$l;
		
			if ($j == 0) $r .= "tmpadd <- $o2; ".$l;
			if ($j >= 1) $r .= "tmpadd <- rbind(tmpadd, $o2); ".$l;
		}
		//$r .= "write.table(tmpadd, file = '/tmp/tmpadd$i', sep = '\\t', quote = F, row.names = FALSE); ".$l;
		
		
		if ($i == 0) $r .= "zz <- tmpadd; ".$l;
		if ($i >= 1) $r .= "zz <- merge(zz, tmpadd, by = c('uv', 'gr')); ".$l;
	}
	$r .= "zz <- zz[order(zz\$uv, zz\$gr), ];".$l;
	$r .= "write.table(zz, file = '/tmp/tmpadd.all', sep = '\\t', quote = F, row.names = FALSE); ".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	//*/

	//_____________________________________________ tab _________________________________________________
	$fe = read2d("/tmp/tmpadd.all");
	$gr1 = count($fe);
	$gr2 = count($fe[0]);

	//Ecke leer
	$fe[0][0] = ""; $fe[0][1] = "";
	//erste Zeile
	for ($j = 0; $j < $gr2; ++$j) $fe[0][$j] = preg_replace("/\\.[xy]/", "", $fe[0][$j]);
	//NA weg
	for ($i = 0; $i < $gr1; ++$i) for ($j = 0; $j < $gr2; ++$j) if(trim($fe[$i][$j]) == "NA") $fe[$i][$j] = "";
	//Spalte 1 + 2 labeln
	for ($i = 0; $i < $gr1; ++$i){
		$a = $fe[$i][0];
		$c = $fe[$i][1];
		if ($a !== "") $fe[$i][0] = givelabel($a);
		$fe[$i][1] = givelabel($a, $c);
	}
	//Doppelte weg
	for ($i = $gr1 - 1; $i > 0; $i--){
		if ($fe[$i][0] == $fe[$i - 1][0]) $fe[$i][0] = "";
		for ($j = 0; $j < $gr2; ++$j){
			$w0 = $fe[0][$j];
			if ($w0 == "p" or $w0 == "shift" or $w0 == "cil" or trim($w0) == "ciu") $gef = 1; else $gef = 0;
			if ($gef == 1 and $fe[$i][$j] == $fe[$i - 1][$j]) $fe[$i][$j] = "";
		}
	}

	//ddd
	
	if ($tests == 1) {$fu .= ",p"; $fe = spalteloeschen($fe, "shift,cil,ciu");}
	if ($tests == 2) {$fu .= ",p,shift,cil,ciu"; }
	$fuf = explode(",", $fu); $fugr = count($fuf);

	//show($fe); return;
	
	//Tabelle
	$t  = $l.$l."<table border = 0 cellspacing = 0 style = 'background-color:#E6E6E6;'>".$l;
	$t .= "<tr><td class = 's_o' ></td><td class = 's_o' ></td>";
	for ($i = 0; $i < $avgr; ++$i) {
		$cl = "s_o";
		if ($i < $avgr - 1) $cl .= " d_r";
		$t .= "<td class = '$cl cc' colspan = $fugr >".givelabel($avf[$i])."</td>";
	}
	$t .= "</tr>";
	//uv, gr, kw
	$t .= "<td class = 's_' ></td><td class = 's_' ></td>";
	$c = 2;
	for ($j = 0; $j < $avgr; ++$j){
		for ($k = 0; $k < $fugr; ++$k){
			$w0 = $fe[0][$c++];
			$cl = "s_c"; if ($k == $fugr - 1 and $j < $avgr - 1) $cl .= " d_r";
			$t .= "<td class = '$cl rr' >".givelabel(trim($w0))."</td>";
		}
	}
	//Zeilen
	for ($i = 1; $i < $gr1 - 1; ++$i){
		$c = 2;
		$t .= "<tr>";
		
		if ($i == 1 or $fe[$i][0] !== "") $cl = "s_o"; elseif ($i  > 1 and $i < $gr1 - 2) $cl = "s_"; else $cl = "s_u1";
		$t .= $l."<td class = '$cl'>".$fe[$i][0]."</td>";
		
		if ($i == 1 or $fe[$i][0] !=="") $cl = "s_o"; elseif ($i  > 1 and $i < $gr1 - 2) $cl = "d_o"; else $cl = "s_u2";
		$t .= $l."<td class = '$cl'>".$fe[$i][1]."</td>";
		
		for ($j = 0; $j < $avgr; ++$j){
			for ($k = 0; $k < $fugr; ++$k){
				$w0 = $fe[0][$c];
				$w = $fe[$i][$c];
				
				if ($w0 == "p") { $w = format2($w, "0.0000"); if ($w <= 0.05) $w = "<b>".$w."</b>"; }
				else if (instr($w0, "count")) $w = $w;
				else $w = format2($w, $fo);
				
				if ($i == 1 or $fe[$i][0] !=="") $cl = "s_or";
				elseif ($i  > 1 and $i < $gr1 - 2) $cl = "d_or";
				else $cl = "s_u2r";
				
				if ($k == $fugr - 1 and $j < $avgr - 1) $cl .= " d_r";
				$t .= "<td class = '$cl'>$w</td>";
				++$c;
			}
		}
		$t .= "</tr>";
	}
	$t .= $l."</table>".$l.$l;
	echop($t);
	write2($t, $ofi);
}
        
//function mytab    
//regression(1, $tb, "age,education", "income", $fo = "0.0");
//regression(1, $tb, "age:education", "v6p", $fo = "0.0"); Doppelpunkt bei interaktionen

// ___________ voll funktionierendes Beispiel _______________
// $fe = getrnd(10, 5);
// $tb = "tmp"; push($fe, "tmp");
// regression(1, $tb, "c[2-3]", "c1", $fo = "0.00");
function alteregression($neucalc, $tb, $uv, $av, $fo = "0.00", $pred){ //Regressionstabelle, mehrere uv, aber nur eine av angeben
	$ti = 0;
	$db = currdb();
	#$ofi = "/eigenes/www/$db/out/regr_".$av."_from_".$uv;       $ofi = preg_replace("/[^a-z0-9\\/_]/", "", $ofi);
	$ofi = ofi($db, "regr_".$av."_from_".$uv);
	$otb = "/eigenes/www/$db/out/regr_".$av."_from_".$uv."_fe"; $otb = preg_replace("/[^a-z0-9\\/_]/", "", $otb);
	#if ($neucalc == 0) {echop(read2($ofi)); return;}

	$l = chr(10);
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";
	dropt2("^tmp_");
	$oo = fromto($tb, "", " where ")."_joined";

	#$uv2 = doppelte_weg(preg_replace("/\\:/", ",", $uv));
	
	if (instr($uv, ":") == 0) $uv = vl4($uv, $tb); $uvf = explode(",", $uv); $uvgr = count($uvf);
	$av = vl4($av, $tb); $avf = explode(",", $av); $avgr = count($avf);
	
	$time_start = microtime(true); $time_start0 = microtime(true);
	$r .= "library(RMySQL); con <- dbConnect(MySQL(), user='backuser', password='backuser99', dbname='$db', host='localhost');".$l;
	$r .= "x <- dbGetQuery(con, 'select $uv, $av from $db.$tb'); x[] <- lapply(x, as.numeric); x = subset(x, complete.cases(x)); attach(x);".$l;
	$u = preg_replace("/,/", " + ", $uv);
	$r .= "rg <- lm($av ~ $u, data = x);".$l;
	$r .= "su <- summary(rg);".$l;
	$r .= "c1 <- su[4];".$l;
	$r .= "c2 <- confint(rg, level = 0.95);".$l;
	$r .= "c12 <- cbind(data.frame(c1),data.frame(c2));".$l;
	$r .= "write.table(c12, file = '$ofi', sep = '\\t', quote = F); ".$l;

	$r .= "colnames(c12) <- c('av".chr(9)."coeffs', 'se', 'tvalue', 'p', 'lo_c', 'up_c');".$l;
	$r .= "write.table(c12, file = '$otb', sep = '\\t', quote = F); ".$l;

	$r .= "r2 <- su\$r.squared; f <- su\$fstatistic[1]; df1 <- su\$fstatistic[2]; df2 <- su\$fstatistic[3];".$l;
	$r .= "p <- pf(f, df1, df2, lower.tail = FALSE);".$l; //pf berechnet p-Wert aus F, df1, df2
	$r .= "p2 <- sprintf('%.4f', p);  if (p <= 0.05) p2 <- paste('<b>p =', p2, '</b>'); if (p < 0.0001) p2 <- '<b>p < 0.0001</b>';  ".$l;
	$r .= "stats <- data.frame(paste('model ', p2, ', R2 =', round(r2, 2), ', df1 =', df1, ', df2 =', df2));".$l;
	$r .= "colnames(stats) <- c('var');".$l;
	$r .= "write.table(stats, file = '$ofi"."_model', sep = '\\t', quote = F); ".$l;
	
	//regression diagnostics  www.statmethods.net/stats/rdiagnostics.html
	//test for normality residuals
	$r .= "sh1 <- shapiro.test(rg\$resid);".$l;
	$r .= "if (sh1\$p.value <  0.0001) shp <- ', p < 0.0001';".$l;
	$r .= "if (sh1\$p.value >= 0.0001) shp <- paste(', p = ', round(sh1\$p.value, 4));".$l; 
	$r .= "sh2 <- cbind('w = ', round(sh1\$statistic, 3), shp)".$l;
	$r .= "write.table(sh2, file = '$ofi"."_normality', sep = '\\t', quote = F, row.names = FALSE, col.names = FALSE); ".$l;
	
	//nonconstant variances
 	$r .= "library(car); nc <- ncvTest(rg); ".$l;
 	$r .= "chi <- sprintf('%.4f', nc[3]);".$l;
 	$r .= "p   <- sprintf('%.4f', nc[5]);".$l;
 	$r .= "nc <- data.frame(paste('Chi = ', chi, ', p =', p));".$l;
 	$r .= "colnames(nc) <- c('nconstvar');".$l;
 	$r .= "write.table(nc, file = '$ofi"."_varianceheterogeneity', sep = '\\t', quote = F, row.names = FALSE, col.names = FALSE); ".$l;
 	
 	//multicollinearity, wenn > 2
 	$r .= "vifl <- round(sqrt(vif(rg)),3);".$l;
 	$r .= "write.table(vifl, file = '$ofi"."_multicollinearity', sep = '\\t', quote = F, row.names = TRUE, col.names = FALSE); ".$l;

	//save predicted
	if ($pred <> "") {
		$r .= "pred <- data.frame(x, preds = round(rg\$fitted.values,2), resids = round(rg\$residuals,2))".$l;
		$r .= "write.table(pred, file = '$pred', sep = '\\t', quote = F, row.names = FALSE, col.names = TRUE); ".$l;
	}
 	
	$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	if ($ti == 1) {$time_end = microtime(true); echo "r: +".round($time_end - $time_start,1)." s"; $time_start = microtime(true); br(1);}
	
	$fu = "var,coeffs,se,tvalue,pwert,lo_c,up_c"; $fuf = explode(",", $fu);
	$tt = "tmpreg";
	myq("drop table if exists $tt");
	myq("create table $tt (".implode(" text, ", $fuf)." text)");
	myq("load data local infile '$ofi' into table $tt fields terminated by '\\t' lines terminated by '\\n' ignore 1 lines");
	
	if ($ti == 1) {$time_end = microtime(true); echo "load: +".round($time_end - $time_start,1)." s"; $time_start = microtime(true); br(1);}

	//_____________________ tab __________________________
	$fu = "var,pwert,coeffs,lo_c,up_c,tvalue,se"; $fuf = explode(",", $fu);
	$fe = readmysql("select ".implode(",", $fuf)." from $tt");
	$fe = function_on_fe($fe, "pwert", "format2(@, '0.0000')");
	$fe = function_on_fe($fe, "^[cluts]", "format2(@, '0.00')");
	$fe = labelheaders($fe);
	$fe = labelcolumn0($fe);

	$ti = label::v($av).": multiple lineare Regression zur Prädiktion.";
	
	#tb($ti);
	$n = new tbnr();
	echop($n->nr($ti." <font color = white>[".fins($uvf)." by ".fins($avf)."]</font>"));

	$cs = "colspan"; $cl = "class = sr"; $al = "align = center";
	$t = "<tr class = s_o ><td></td><td $cl $cs = 6 $al>Multiple Regression: <b>".givelabel($av)."</b></td></tr>";
	showneu($fe, "1,3", "350,100", "^0$:.:s_u,.:.:d_o,last:.:s_u", $t);

	//assumption tests
	$t .= "<br>significance of overall model: ".fromto(read2($ofi."_model"), "model ", " ,");
	$t .= "<br><b>assumption checks of multiple regression (in case of violations declare this model as descriptive)</b>".$t2;
	
	$t2 = read2($ofi."_normality");
	$t2 = preg_replace("/\\t/", "", $t2);
	$t .= "<br>1. normality test (of residuals, violation of this assumption if p <u><</u> 0.05, test = Shapiro-Wilks): ".$t2;
	
	$t2 = read2($ofi."_varianceheterogeneity");
	$t2 = preg_replace("/\\s, /", ", ", $t2);
	$t .= "<br>2. constant variances (violation of this assumption if p <u><</u> 0.05): ".$t2;

	$t2 = read2($ofi."_multicollinearity");
	$t2 = preg_replace("/\\t/", " v = ", $t2);
	$t2 = preg_replace("/\\n/", ", ", $t2);
	$t2 = kommaweg(trim($t2));
	$t2 = givelabel_list3($t2);
	$t .= "<br>3. multicollinearity (violation of this assumption if value > 2): ".$t2;
	
	//regression equation
	$fe = read2d($otb);
	$gr1 = count($fe); $e = "";
	for ($i = 1; $i < $gr1 - 1; ++$i) $e .= " + ".round($fe[$i][1],2)." * ".$fe[$i][0].lz(2);
	$e = preg_replace("/\+ \-/", " - ", $e);
	$e = preg_replace("/\* \(Intercept\)/", "", $e);
	$t .= "<br>4. Equation: ".$av." = ".$e;

	echop($t); #write2($t, $ofi);
	
	if ($ti == 1) {
		$time_end = microtime(true); echo "tab: +".round($time_end - $time_start,1)." s"; $time_start = microtime(true); br(1);	
		$time_end = microtime(true); echo "gesamt: ".round($time_end - $time_start0,1)." s";
	}
}

function regression_ordinal($neucalc, $tb, $uv, $av, $fo = "0.00"){ //Regressionstabelle, mehrere uv, aber nur eine av angeben
	$ti = 0;
	$db = currdb();
	$ofi = "/eigenes/www/$db/out/regr_ord_".$av."_from_".$uv;       $ofi = preg_replace("/[^a-z0-9\\/_]/", "", $ofi);
	$otb = "/eigenes/www/$db/out/regr_ord_".$av."_from_".$uv."_fe"; $otb = preg_replace("/[^a-z0-9\\/_]/", "", $otb);
	if ($neucalc == 0) {echop(read2($ofi)); return;}

	$l = chr(10);
	if (instr($tb," where ")) $wt = "and"; else $wt = "where";
	dropt2("^tmp_");
	$oo = fromto($tb, "", " where ")."_joined";

	$uv2 = doppelte_weg(preg_replace("/\\:/", ",", $uv));

	if (instr($uv, ":") == 0) $uv = vl3($uv, $tb); $uvf = explode(",", $uv); $uvgr = count($uvf);
	$av = vl3($av, $tb); $avf = explode(",", $av); $avgr = count($avf);
	
	$r .= "library(RMySQL); library(MASS); con <- dbConnect(MySQL(), user = 'backuser', password = 'backuser99', dbname = '$db', host = 'localhost');".$l;
	$r .= "x <- dbGetQuery(con, 'select $uv2, $av from $db.$tb'); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
	
	$u = preg_replace("/,/", " + ", $uv);
	$r .= "fit <- polr(factor($av) ~ $u, data = x, Hess = TRUE);".$l;

	$r .= "t <- coef(summary(fit));".$l;
	$r .= "p <- pnorm(abs(t[, 't value']), lower.tail = FALSE) * 2;".$l;
	$r .= "t <- cbind(t, 'p value' = p);".$l;
	$r .= "ci <- confint.default(fit);".$l;
	$r .= "e <- exp(cbind(OR = coef(fit), ci));".$l;
	$r .= "t <- merge(t, e, by = 0, all = TRUE);".$l;
	//$r .= "t <- t[,!(names(t) %in% c('Row.names') )];".$l;	
	$r .= "write.table(t, file = '/tmp/tmpregrord', sep = '\\t', quote = F, row.names = FALSE); ".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);

	//_____________________ tab __________________________
	$fe = read2d("/tmp/tmpregrord");
	$gr1 = count($fe);
	$gr2 = count($fe[0]); $fe[0][0] = "";

	$t  = $l.$l."<table border = 0 cellspacing = 0 style = 'background-color:#E6E6E6;'>".$l;
	$t .= "<tr>";
	for ($j = 0; $j < $gr2; ++$j) $t .= "<td class = s_o>".lz(3).$fe[0][$j].lz(3)."</td>";
	$t .= "</tr>";
	for ($i = 1; $i < $gr1 - 1; ++$i){
		$t .= "<tr>";
		if ($i == 1) $cl = "s_o cc";
		if ($i >  1) $cl = "d_o cc";
		if ($i == $gr1) $cl = "d_u cc";
		for ($j = 0; $j < $gr2; ++$j){
			$w = $fe[$i][$j];
 			if (instr($w, "NA")) $w = "-";
			if ($j > 0 and is_numeric($w)) $w = sprintf("%.3f", $w);
			$t .= "<td class = '$cl'>".$w."</td>";
		}
		$t .= "</tr>";
	}
	$t .= "</table>";
	echop($t);
	write2($t, $ofi);
}

//function mytab
//      hl  lo_ci  up_ci
// av1
// av2
// av3
// av4
//1 Kontrastmaß zw. 2 Gruppen und p + CI
//hodges(1, $tb, "gr", "age");
//_________ voll funktionierendes Beispiel _____________
// $fe = getrnd(100, 5);
// comp($fe, "if (@c1@ <= 50) @gruppe@ = 1; else @gruppe@ = 0;"); 
// hodges(1, $fe, "gruppe", "^c[2-4]");
function hodges($neucalc, $fe, $uv, $av, $fo = "0.00"){ //Hodges-Lehmann Kontaste, av nach unten gestapelt, nur 1 uv angeben
	$fe = getmat2($fe, $av.",".$uv);
	$uv = vl($fe[0], $uv);
	$av = vl($fe[0], $av); $avgr = count($av);
		
	$l = chr(10);
	
	for ($j = 0; $j < $avgr; ++$j){
		$a = $avf[$j];
		$r .= "w <- wilcox.exact(x\$$av[$j] ~ x\$$uv[0], conf.int = TRUE);".$l;
		$r .= "y <- data.frame(cbind('$av[$j]', w\$estimate, w\$conf.int[2], w\$conf.int[1], w\$p.value ));".$l;
		$r .= "colnames(y) <- c('av', 'hl', 'hll', 'hlu', 'pwert');".$l;
		if ($j == 0) $r .= "y2 <- y; ".$l;
		if ($j >  0) $r .= "y2 <- rbind(y2, y); ".$l;
	}
	$o = "/tmp/hodges.txt";
	if (file_exists($o)) unlink ($o);	
	$r .= "write.table(y2, file = '$o', sep = '\\t', quote = F, row.names = FALSE); ".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi); exec("R --no-save --slave -q < $fi > $fi.out");

	$fe = read2d($o);
	$fe = selif3($fe, "@av@ <> ''");
	comp($fe, "@hl@ = - @hl@;");
	comp($fe, "@hll@ = - @hll@;");
	comp($fe, "@hlu@ = - @hlu@;");
	
	$fe = function_on_fe($fe, "^hl", "format2(@, '0.0'   )");
	$fe = function_on_fe($fe, "^p" , "format2(@, '0.0000')");
	comp($fe, "if (@pwert@ <= 0.05) @pwert@ = '<b>'.@pwert@.'</b>';");
	
	comp($fe, "@av@ = givelabel(@av@);");
	$fe = labelheaders($fe); $fe[0][0] = "";
	writefe($fe, $ofi);
	showneu($fe);
}



//function mytab
//      p meandiff lo_ci up_ci  hl lo_ci up_ci  roc lo_ci up_ci
// av1
// av2
// av3
// av4
//mean_hodges_roc(1, $tb, "gr", "age");
function mean_hodges_roc($neucalc, $tb, $uv, $av, $fo = "0.00"){ //Mean-CI, Hodges + ROC nach unten gestapelt, nur 1 uv angeben, http://cran.r-project.org/web/packages/pROC/README.html
	$db = currdb();
	$l = chr(10);
	
	$ofi = ofi($db, "mean_hodges_roc_".$tb."_".$uv."_".$av);
	if ($neucalc == 0) { show(read2d($ofi)); return;}
	
	$uv = vl4($uv, $tb); $uvf = explode(",", $uv); $uvgr = count($uvf);
	$av = vl4($av, $tb); $avf = explode(",", $av); $avgr = count($avf);
	$fuf = explode(",", $fu); $fugr = count($fuf);
	
	$r .= "con <- dbConnect(dbDriver('MySQL'), dbname = '$db', username = 'backuser', password = 'backuser99', host = 'localhost');".$l;
	$r .= "library(exactRankTests);".$l;
	$r .= "library(pROC);".$l;
	$r .= "x <- dbGetQuery(con, 'select $uv + 0, $av from $tb');".$l;
	$r .= "x <- subset(x, complete.cases(x));".$l;
	
	for ($j = 0; $j < $avgr; ++$j){
		$a = $avf[$j];
		$r .= "w <- wilcox.exact(x\$$a ~ x\$$uv, conf.int = TRUE);".$l;
		$r .= "t <- t.test(x\$$a ~ x\$$uv);".$l;
		$r .= "r1 <- roc(x\$$a ~ x\$$uv); r2 <- ci(r1);".$l;
		
		$r .= "y <- data.frame(cbind('$a', w\$p.value, t\$estimate[2] - t\$estimate[1], -t\$conf.int[2][1], -t\$conf.int[1][1], -w\$estimate, -w\$conf.int[2], -w\$conf.int[1], r2[2], r2[1], r2[3]));".$l;
		$r .= "colnames(y) <- c('av', 'pwert', 'mdiff', 'mdiffl', 'mdiffu', 'hl', 'hll', 'hlu', 'roc', 'rocl', 'rocu');".$l;

		if ($j == 0) $r .= "y2 <- y; ".$l;
		if ($j >  0) $r .= "y2 <- rbind(y2, y); ".$l;
	}
	$o = "/tmp/mean_hodges_roc.txt";
	if (file_exists($o)) unlink ($o);	
	$r .= "write.table(y2, file = '$o', sep = '\\t', quote = F, row.names = FALSE); ".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi); exec("R --no-save --slave -q < $fi > $fi.out");

	//_____________________ tab __________________________

	$fe = read2d($o);
 	$fe = selif3($fe, "@av@ <> ''");
 	
 	$fe = function_on_fe($fe, "^hl|^mdiff", "format2(@, '$fo')");
 	$fe = function_on_fe($fe, "^roc", "format2(@, '0.00')");
 	$fe = function_on_fe($fe, "^p" , "format2(@, '0.0000')");
 	comp($fe, "if (@pwert@ <= 0.05) @pwert@ = '<b>'.@pwert@.'</b>';");
 	
 	comp($fe, "@av@ = givelabel(@av@);");
 	$fe = labelheaders($fe); $fe[0][0] = "";
 	writefe($fe, $ofi);
	show($fe);
}

function cronbachalpha($tb, $av){
	$db = currdb();
	$l = chr(10);
	$av = vl4($av, $tb);
	$r .= "con <- dbConnect(MySQL(), user = 'backuser', password = 'backuser99', dbname = '$db', host = 'localhost');".$l;
	$r .= "x <- dbGetQuery(con, 'select $av from $db.$tb'); x[] <- lapply(x, as.numeric);".$l;
	$r .= "library(cocron); cb <- cronbach.alpha(x, standardized = FALSE);";
	$r .= "write.table(cb, file = '/tmp/cronbach', sep = '\\t', quote = F, row.names = FALSE, col.names = FALSE);".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	return format2(round(read2("/tmp/cronbach"), 4), "0.0000");
}

function itemanalysis($tb, $v, $elimgr = 0.5){
	$db = currdb();
	$l = chr(10);
	$v = vl4($v, $tb); $vf = explode(",", $v);
	$r .= "con <- dbConnect(MySQL(), user = 'backuser', password = 'backuser99', dbname = '$db', host = 'localhost');".$l;
	$r .= "x <- dbGetQuery(con, 'select $v from $db.$tb'); x[] <- lapply(x, as.numeric);".$l;
	$r .= "library(psychometric); o <- item.exam(x);".$l;
	$r .= "write.table(cbind(row.names(o), o), file = '/tmp/itemanalysis', sep = '\\t', quote = F, row.names = FALSE, col.names = TRUE);".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	$fe = read2d("/tmp/itemanalysis");
	$fe = getmat2($fe, "^row,^Item\.[Tt]");
	$fe = function_on_fe($fe, "^Item", "format2(@, '0.000')");
	#$fe = function_on_fe($fe, "^Item", "formatg(@, 0.5)");
	$fe[0][0] = "vars";
	$fe = selif3($fe, "@vars@ <> ''");
	$fe[0] = array("", "Korr. mit<br>Summenscore", "Korr. mit Summenscore<br>ohne dieses Item");
	for ($i = 1; $i < count($fe); ++$i){
		if ($fe[$i][1] < $elimgr) $e[] = $fe[$i][0];
	}
	$fe = labelcolumn0($fe);
	show($fe);
	
	$vf = explode(",", $v);
	if (count($e) > 0) {
		$o = implode(",", $e);
		$rest = inlist3($o, $v);
		echop("Anmerkung: Korrelation Item mit Skala zu gering: $o. Neue Skala daher aus folgenden Items: ".$rest);
		return $rest;
	} else return $v;
}

//corr table
//corr(1, $tb, "v1,v2,v2", 2);
function corr($neu, $tb, $av, $me = 1, $colwidth = "350,350,50"){ //jeder gegen jeden, nach unten gestapelt
      if (is_array($tb)) {$fe = getmat2($tb, $av); push($fe, "tmp_freq"); $tb = "tmp_freq";}
	if ($me == 1) $me = "pearson"; else $me = "spearman";
	$db = currdb();
	$l = chr(10);
	$ofi = ofi($db, "corr_".$tb."_".$av);
	if ($neu == 1) {
		$av = vl4($av, $tb); $avf = explode(",", $av); $avgr = count($avf);
		$r .= "con <- dbConnect(MySQL(), user = 'backuser', password = 'backuser99', dbname = '$db', host = 'localhost');".$l;
		$r .= "x <- dbGetQuery(con, 'select $av from $db.$tb'); x[] <- lapply(x, as.numeric);".$l;
		$z = 0;
		for ($i = 0; $i < $avgr; ++$i){
			for ($j = 0; $j < $avgr; ++$j){
				if ($j > $i) {
					++$z; $a1 = $avf[$i]; $a2 = $avf[$j];
					
					$r .= "x2 <- x[, c('$a1', '$a2')];
						 x2 = subset(x2, complete.cases(x2)); n <- nrow(x2);
						 colnames(x2) <- c('$a1', '$a2');
						 c <- cor.test(x2\$$a1, x2\$$a2, method = '$me');
						 y <- data.frame(cbind('$a1', '$a2', c\$estimate, c\$p.value, n ));
						 colnames(y) <- c('corr_av1', 'corr_av2', 'corr', 'p', 'n');".$l;
					       
					if ($z == 1) $r .= "tmpadd <- y; ".$l;
					if ($z >  1) $r .= "tmpadd <- rbind(tmpadd, y); ".$l;
				}
			}
		}
		$r .= "write.table(tmpadd, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); rm(list = ls());".$l;
		//$fi = "/tmp/r.cmd"; write2($r, $fi); exec("R --no-save --slave -q < $fi > $fi.out"); 
		$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	$fe = read2d($ofi);
	$fe = selif3($fe, "@corr_av1@ <> ''");
	$fe = function_on_fe($fe, "^corr|^p", "format2(@, '0.0000')");
	comp($fe, "if (is_numeric(@p@) and @p@ <= 0.05) @p@ = '<b>'.@p@.'</b>';");
	comp($fe, "if (@corr@ == 'NA') @corr@ = '-';");
	comp($fe, "if (@p@    == 'NA') @p@    = '-';");
	comp($fe, "@corr_av1@ = givelabel(@corr_av1@);");
	comp($fe, "@corr_av2@ = givelabel(@corr_av2@);");
	$fe = labelheaders($fe); $fe[0][0] .= " (".ucfirst($me)."'s correlation)";
	tb("Berechnung von Zusammenhangsmaßen (n = number of cases, d.h. Stichprobengröße, p = epxlor. Signifikanztest für den Korrelationskoeffizienten).");
	show($fe, "1, 1, 3", $colwidth);
}

//corr1vs2(1, $tb, "v1", "v2,v3", 2);
function corr1vs2($neu, $tb, $av1, $av2, $me = 1, $colwidth = "550,60"){ //einer gegen alle, nach unten gestapelt
      if (is_array($tb)) {$fe = getmat2($tb, $av1.",".$av2); push($fe, "tmp_freq"); $tb = "tmp_freq";}
      if ($av1 == "" or $av2 == "") return;      
	if ($me == 1) $me = "pearson"; else $me = "spearman";
	$db = currdb();
	$l = chr(10);
	$ofi = ofi($db, "corr_".$tb."_".$av);
	if ($neu == 1) {
		$av1 = vl4($av1, $tb); $avf1 = explode(",", $av1); $avgr1 = count($avf1);
		$av2 = vl4($av2, $tb); $avf2 = explode(",", $av2); $avgr2 = count($avf2);
		$r .= "con <- dbConnect(MySQL(), user = 'backuser', password = 'backuser99', dbname = '$db', host = 'localhost');".$l;
		$r .= "x <- dbGetQuery(con, 'select $av1, $av2 from $db.$tb'); x[] <- lapply(x, as.numeric);".$l;

		for ($i = 0; $i < $avgr2; ++$i){
			$a1 = $avf1[0]; $a2 = $avf2[$i];
			$r .= "x2 <- x[, c('$a1', '$a2')];
				x2 = subset(x2, complete.cases(x2)); n <- nrow(x2);
				colnames(x2) <- c('$a1', '$a2');
				c <- cor.test(x2\$$a1, x2\$$a2, method = '$me');
				y <- data.frame(cbind('$a1', '$a2', c\$estimate, c\$p.value, n ));
				colnames(y) <- c('corr_av1', 'corr_av2', 'corr', 'p', 'n');".$l;
				if ($i == 0) $r .= "tmpadd <- y; ".$l;
				if ($i >  0) $r .= "tmpadd <- rbind(tmpadd, y); ".$l;
		}

		$r .= "write.table(tmpadd, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE); rm(list = ls());".$l;
		$fi = "/tmp/r.cmd"; write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	}
	#echop("<textarea id=mytextarea rows=14 cols=110 wrap=off>$r</textarea> ").$l;
	$fe = read2d($ofi);
	$fe = selif3($fe, "@corr_av1@ <> ''");
	$fe = function_on_fe($fe, "^corr|^p", "format2(@, '0.0000')");
	comp($fe, "if (is_numeric(@p@) and @p@ <= 0.05) @p@ = '<b>'.@p@.'</b>';");
	comp($fe, "if (@corr@ == 'NA') @corr@ = '-';");
	comp($fe, "if (@p@    == 'NA') @p@    = '-';");
	comp($fe, "@corr_av1@ = givelabel(@corr_av1@);");
	comp($fe, "@corr_av2@ = givelabel(@corr_av2@);");
	$fe = getmat2($fe, "^((?!corr_av1).)*$");
	$g = givelabel($av1);
	$fe = labelheaders($fe); $fe[0][0] = $g.": Korrelation nach ".ucfirst($me)." zu ...";
	tb($g.": Korrelation nach ".ucfirst($me).", p = epxlor. Signifikanztest für den Korrelationskoeffizienten (explorativ signifikant wenn p <u><</u> 0.05 ).");
	showneu($fe, "1, 1, 3", $colwidth);
}

//factor(1, $tb, "v[1-3", $fo = "0.000", 5);
function alt_factor($neucalc, $fe, $av, $fo = "0.00", $factorzahl = 5, $short = 1){
	$db = currdb();
	$ofi = ofi($db, "factor_ ".$av);	
	if ($neucalc == 0) { echop(read2($ofi)); return;}
	$l = chr(10);
	$t = chr(9);
	
	$avf = vl($fe[0], $av); $av = implode(",", $avf); $avgr = count($avf);
	$fe = getmat2($fe, $av);
	$f = "/tmp/tmp.dat"; writefe($fe, $f, 1);
	
	$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
	$r .= "fit <- prcomp(x, cor = TRUE);".$l;
	$r .= "n <- row.names(fit\$rotation);".$l;
	$r .= "d <- data.frame(rbind(fit\$rotation, fit\$sdev));".$l;
	$r .= "colnames(d)[1] <- 'vars".$t."PC1';".$l;
	$r .= "write.table(d, file = '/tmp/tmpfactor', sep = '\\t', quote = FALSE, row.names = TRUE); ".$l;
	
	$r .= "write.table(cbind(x,round(fit\$x,2)), file = '/tmp/tmpfactorvalues', sep = '\\t', quote = F, row.names = FALSE); ".$l;
	$r .= "rm(fit);".$l;
	$fi = "/tmp/r.cmd"; write2($r, $fi); 
	$s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);

	$fe = read2d("/tmp/tmpfactor");
	$gr = $factorzahl;
	$fe = getmat2($fe, "vars,^PC[1-$gr]$");
	$fe = function_on_fe($fe, "^PC", "format2(@, '$fo')");
	$fe = function_on_fe($fe, "^PC", "formatg(@, 0.4, '$fo')");

	$fe = labelcolumn0($fe);
	$fe = labelheaders($fe);
	
	$fe[count($fe) - 2][0] = "Eigenwerte";
	$fe = selif3($fe, "@vars@ !== ''"); $fe[0][0] = "";
	tb("Ergebnisse der Faktorenanalyse mittels Hauptkomponenten-Analyse (principle component analysis) nach Thurstone (Bortz, 2003) und 
		Rotation in Einfachstruktur. Die Werte sind Korrelationskoeffizienten mit dem Faktor. Zu geringe Koeffizienten, d.h. kleiner 0.40, sind in grau dargestellt.");
	show($fe);
}

function leadingweg($s){ //entfernt in String mit vielen Zeilenumbrüchen leading blanks + tabs
	$fe = explode(chr(10), $s);
	$fe = preg_replace('/^(\\s+|\\t+)/', '' ,$fe);
	return implode(chr(10), $fe);
}

function logisticvalue($fe, $av, $uv){
	$l = chr(10);
	$ofi = "/eigenes/downs/temp/logisticvalue.$uv.out";
	$fe = data::get($fe, $av.",".$uv);
	$f = "/eigenes/downs/temp/logistictmp.".$uv.".dat"; export::asc($fe, $f);
	$r .= "x <- read.table('$f', header = TRUE); x[] <- lapply(x, as.numeric); x <- subset(x, complete.cases(x));".$l;
	$r .= "ll <- glm($av ~ $uv, data = x, family = binomial(link='logit'));".$l;
	$r .= "sum.coef <- summary(ll)\$coef;".$l;
	$r .= "p        <- sum.coef[2,4];".$l;
	$r .= "odds     <- exp(sum.coef[2,1]);".$l;
	$r .= "se       <- sum.coef[2,2];".$l;
	$r .= "lower.ci <- odds - 1.96 * se;".$l;
	$r .= "upper.ci <- odds + 1.96 * se;".$l;
	$r .= "nevents  <- sum2(ll\$data$$av);".$l;
	$r .= "n        <- length(ll\$data$$av);".$l;
	$r .= "y <- data.frame(cbind('$uv', p, odds, lower.ci, upper.ci, nevents, n));".$l;
	$r .= "colnames(y) <- c('var_log', 'p_log', 'or_log', 'lci_log', 'uci_log', 'nev_log', 'n_log');".$l;
	$r .= "write.table(y, file = '$ofi', sep = '\\t', quote = F, row.names = FALSE);".$l;
	$fi = "/eigenes/downs/temp/r.logistic".$uv.".cmd"; write2($r, $fi); 
	
	exec("chmod 777 '$fi'; sudo Rscript '$fi'"); #write2($r, $fi); $s = Rserve_connect(); Rserve_eval($s, "{ $r }"); Rserve_close($s);
	
	$fe = read3d($ofi);
	#show3($fe);
	$fe = data::filter($fe, "@var_log@ <> ''");
	$fe = data::comp($fe, "@alle@ = 1;");
	return $fe;
}

function echop($t){echo chr(10).chr(10)."<p>$t</p>";}

function md($t, $co = "black"){echo "<p style = 'color: $co' >$t</p>";}

function ok(){md("ok"); die;}

// ______________________________________________________________________ Literatur ________________________________________________________________________________________________

// erst  litread($pfad); dropt2b("^paper");
// echo("Die Werte stiegen an, wie ".lit("^(?=.*Fother*)(?=.*200)*")." beschrieben, dann sanken sie, wie schon ".lit("^(?=.*Calvet*)(?=.*200)*")." zeigten.");
// am Ende litverz();
function litread($d){
	$fe = getDirContents($d);
	$t = "papers";
	myq("drop table if exists $t");
	myq("create table if not exists $t (lfn int auto_increment, id int, paper varchar(1000), authors varchar(250), firstau varchar(250), year varchar(5), unique key paperkey (paper), primary key (lfn)) engine = myisam");
	for ($j = 0; $j < count($fe); ++$j){
		myq("insert into $t (paper) values ('".str_replace($d, "", $fe[$j])."')");
	}
	#show3(import::mysql("select * from $t")); return;
		
	$fe = import::mysql("select * from ".$t);
	for ($i = 0; $i < count($fe["paper"]); ++$i){
		$p = $fe["paper"][$i];
		$id = fromto($p, "_id", "_");
		if (preg_match("/_id[0-9]/", $p)) myq("update $t set id = '$id' where instr(paper, '_id".$id."_')" );
	}
	#show3(import::mysql("select * from $t"));
	
	for ($i = 0; $i < count($fe["paper"]); ++$i){
		$p = $fe["paper"][$i];
		if (!preg_match("/_id[0-9]/", $p) and preg_match("/\.pdf/", $p) ){
			$mx = recwert("select max(id) from ".$t) + 1;
			$n = preg_replace("/\.pdf/", "_id".$mx."_.pdf", $p);
			md ($p." --> ".$n);
			myq("update $t set paper = '$n' where paper = '$p'" );
			if (rename($p, $n) == false) {
				echo (" ... <b>das geht leider nicht umzubenennen</b>");
				exec("mv '$p' '$n' > /eigenes/downs/temp/php.error_log 2>&1 ");
			}
			return;
		}
	}
	#show3(import::mysql("select * from $t")); return;
	
	$fe = import::mysql("select * from ".$t);
	for ($i = 0; $i < count($fe["paper"]); ++$i){
		$p = $fe["paper"][$i];
		$a = fromto($p, "", "."); $fi = fromto($a, "", " ");
		$id = fromto($p, "_id", "_");
		$y = preg_replace ("/(.+) ([0-9]{4,});(.+)/", "\$2", $p);
		myq("update $t set authors = '".fromto($p, "/lit/", ".")."' where instr(paper, '_id".$id."_')" );
		myq("update $t set firstau = '$fi' where instr(paper, '_id".$id."_')" );
		myq("update $t set year = '$y' where instr(paper, '_id".$id."_')" );
		
	}
	return;
	#show3(import::mysql("select * from $t")); return;
	md("ok");
}

// search for 'one' and 'two' with lookaheads: preg_match('/^(?=.*one)(?=.*two)/i', "one, two, three");
# comm("Die Mittelwerte stiegen deutlich an, wie ".lit("^(?=.*Bortz)(?=.*1984)")." beschrieb.");
function lit($such, $d){
	$b = recwert("select paper from papers where preg_position('/$such/i', paper) limit 1"); $b0 = $b;
	#$b = recwert("select paper from papers where paper regexp '/$such/' limit 1"); $b0 = $b;
	
	$tb = "papers_cited";
	myq("create table if not exists $tb (lfn int auto_increment, paper varchar(1000), unique key paperkey (paper), primary key (lfn))");
	myq("insert into $tb (paper) values ('$b') on duplicate key update paper = '$b'");
	
	$y = preg_replace("/^.* (\d\d\d\d);.*$/", "\$1", $b);
	$b = ft($b, "\.");
	
	$fe1 = explode(",", $b);
	
	for ($j = 0; $j < count($fe1); ++$j){$fe2 = explode(" ", trim($fe1[$j])); $a[] = $fe2[0]; }
	$gr = count($a);
	
	$f = "lit/".$b0;
	$t1 = "<a href = '$f' target = _blank>"; $t2 = "</a>";
	
	if ($gr == 1) return $t1.$a[0]." (".$y.")".$t2;
	if ($gr == 2) return $t1.$a[0]." & ".$a[1]." (".$y.")".$t2;
	if ($gr >= 3) return $t1.$a[0].", ".$a[1]." et al. (".$y.")".$t2;
}

function litverz(){
	$tb = "papers_cited";
	myq("create table if not exists $tb (lfn int auto_increment, lit varchar(1000), unique key litkey (lit), primary key (lfn))");
	$rs = myq("select paper from ".$tb." where paper <> ''");
	while($row = mysql_fetch_row($rs)) {
		$k0 = $row[0];
		$k = preg_replace("/\.pdf$/", "", $k0);
		$k = preg_replace("/_id.+/", "", $k);
		$k = preg_replace('/;$/', '', $k);
		$k = preg_replace("/_f_/", "?", $k);
		$k = preg_replace("/_d_/", ":", $k);
		echop("<a name='$k' href = 'lit/$k0' target = _blank >$k.</a>".chr(10));
	}
}

// __________________________________________________________ Tabellennummern und Referenzen _______________________________________________________________________________________
/*
dropt2b("^tabs"); $tb = "daten01"; zufallsdaten4(15, 20, $tb);
echop("In der nächsten ".tbref(1)." sieht man folgendes ...");
desc($tb, "c[6-7]");
desc($tb, "c[1-2]$");
echop("In der nächsten ".tbref(1)." sieht man folgendes ..., aber oben in ".tbref(-1)." war es anders.");
desc($tb, "c[3-4]");
echop("In der obigen ".tbref(-1)." sieht man folgendes ...");
echop("In der vorletzten ".tbref(-2)." sieht man folgendes ...");
showt("tabs");
tbfill();
//*/

// voll funktionierendes Beispiel
//   dropt2b("^tabs");
//   echop("In der nächsten ".tbref(1)." sieht man ...");
//   desc($tb, "c[6-7]");
//   echop("In der obigen ".tbref(-1)." sieht man ...");
//   tbfill();
function tb($text){
	$tb = "tabs";
	$tabna = "t".zufallsstring(5);
	myq("create table if not exists $tb (lfn int auto_increment, tabnr int, tabna text, refto int, refna text, primary key (lfn))");
	$mx = recwert("select count(tabna) from $tb where tabnr <> ''") + 1;
	myq("insert into $tb (tabnr, tabna) values ('$mx', '$tabna')");
	md("<table border = 0 style = 'box-shadow: none;' ><tr><td width = 110px valign = top ><p2 id = $tabna>Tabelle $mx</p2>: </td><td>$text</td></tr></table>");
}

function tbref($ref){
	$tb = "tabs";
	myq("create table if not exists $tb (lfn int auto_increment, tabnr int, tabna text, refto int, refna text, primary key (lfn))");
	$mx = recwert("select count(refna) from $tb where refna <> ''") + 1;
	$refna = "tb".$mx;
	$te = "<b><font color = blue>...</font></b>";
	if ($ref !== "") {myq("insert into $tb (refna, refto) values ('$refna', $ref)"); return "<p2 class = $refna id = tb".zufallsstring(5)." >$te</p2>"; }
}

function tbfill(){
	$tb = "tabs";
	myq("create table if not exists $tb (lfn int auto_increment, tabnr int, tabna text, refto int, refna text, primary key (lfn))");
	$fe = get("select lfn, refto, refna from $tb where refto <> ''");
	#show($fe);
	for ($i = 1; $i < count($fe); ++$i) {
		$lfn   = $fe[$i][0];
		$refto = $fe[$i][1];
		$refna = $fe[$i][2];
		if ($refto > 0) {
			$fe2 = get("select tabna, tabnr from $tb where lfn > $lfn and tabna <>'' order by tabnr limit $refto");
			$tabna = $fe2[$refto][0];
		} else {
			$fe2 = get("select tabna, tabnr from $tb where lfn < $lfn and tabna <>'' order by lfn desc limit ".abs($refto));
			$tabna = $fe2[abs($refto)][0];
		}
		echop("<script type = 'text/javascript'>
				d = document; e = d.getElementsByClassName('$refna');
				for(var i = 0; i < e.length; i++) d.getElementById(e[i].id).innerHTML = '<a href = #$tabna>' + d.getElementById('$tabna').innerHTML + '</a>'; 
			</script>");
	}
}

function fig($text){
	$tb = "figs";
	$figna = "f".zufallsstring(5);
	myq("create table if not exists $tb (lfn int auto_increment, fignr int, figna text, refto int, refna text, primary key (lfn))");
	$mx = recwert("select count(figna) from $tb where fignr <> ''") + 1;
	myq("insert into $tb (fignr, figna) values ('$mx', '$figna')");
	echop("<table border = 0 style = 'box-shadow: none;' ><tr><td width = 110px valign = top ><p2 id = $figna>Abbildung $mx</p2>: </td><td>$text</td></tr></table>");
}

function figref($ref){
	$tb = "figs";
	myq("create table if not exists $tb (lfn int auto_increment, fignr int, figna text, refto int, refna text, primary key (lfn))");
	$mx = recwert("select count(refna) from $tb where refna <> ''") + 1;
	$refna = "fig".$mx;
	$te = "<b><font color = blue>...</font></b>";
	if ($ref !== "") {myq("insert into $tb (refna, refto) values ('$refna', $ref)"); return "<p2  class = $refna id = fig".zufallsstring(5).">$te</p2>"; }
}

function figfill(){
	$tb = "figs";
	myq("create table if not exists $tb (lfn int auto_increment, fignr int, figna text, refto int, refna text, primary key (lfn))");
	$fe = get("select lfn, refto, refna from $tb where refto <> ''");
	for ($i = 1; $i < count($fe); ++$i) {
		$lfn   = $fe[$i][0];
		$refto = $fe[$i][1];
		$refna = $fe[$i][2];
		if ($refto > 0) {
			$fe2 = get("select figna, fignr from $tb where lfn > $lfn and figna <>'' order by fignr limit $refto");
			$figna = $fe2[$refto][0];
		} else {
			$fe2 = get("select figna, fignr from $tb where lfn < $lfn and figna <>'' order by lfn desc limit ".abs($refto));
			$figna = $fe2[abs($refto)][0];
		}
		echop("<script type = 'text/javascript'>
				d = document; e = d.getElementsByClassName('$refna');
				for(var i = 0; i < e.length; i++) d.getElementById(e[i].id).innerHTML = '<a href = #$figna>' + d.getElementById('$figna').innerHTML + '</a>'; 
			</script>");
	}
}

// __________________________________________________________ neue Tabellennummern und Referenzen _______________________________________________________________________________________

class tbnr {
	function __construct() {
		$this->t = "tablerefs";
		myq("drop table ".$this->t);
		myq("create table if not exists ".currdb().".".$this->t." (lfn int auto_increment, tabnr int, tabna text, refto int, refna text, tabfe text, primary key (lfn)) engine = myisam");
	}
	
	function nr($text, $fe){
		$mx = recwert("select count(tabna) from $this->t where tabnr <> ''") + 1;
		$tabna = "t".$mx;
		#myq("insert into $this->t (tabnr, tabna, tabfe) values ('$mx', '$tabna', '".str::fe2str($fe)."')");
		return "<table border = 0 style = 'box-shadow: none;' ><tr><td width = 110px valign = top><p2 id = $tabna>Tabelle $mx</p2>: </td><td>$text</td></tr></table>";
	}

	function ref_alt($ref){
		$t = $this->t;
		$mx = recwert("select count(refna) from $t where refna <> ''") + 1;
		$refna = "r".$mx;
		myq("insert into $t (refna, refto) values ('$refna', $ref)");
		
		$fe = import::mysql("select * from ".$t."final where refna = '$refna' or tabna <> ''");
		for ($i = 0; $i < count($fe["lfn"]); ++$i) if ($fe["refna"][$i] == $refna) {
			$nr = $fe["tabnr"][$i + $ref];
			if (is_numeric($nr)) return "<a href = '#t$nr' >Tabelle $nr</a>";
		}
		return "<a><b>.....</b></a>";
	}
	
	function r($ref, $colname, $row){
		$t = $this->t; $mx = recwert("select count(refna) from $t where refna <> ''") + 1;
		$refna = "rt".$mx;
		myq("insert into $t (refna, refto) values ('$refna', $ref)");
		$fe = import::mysql("select * from ".$t."final where refna = '$refna' or tabna <> ''");
		for ($i = 0; $i < count($fe["lfn"]); ++$i) if ($fe["refna"][$i] == $refna) {
			$nr = $fe["tabnr"][$i + $ref];
			$s = recwert("select tabfe from ".$t."final where tabnr = '$nr'");
			$tfe = str::str2fe($s);
			return preg_replace("/\<b\>|\<\/b\>/", "", $tfe[$colname][$row]);
		}
		return "<a><b>.....</b></a>";
	}
	
	// echop("Das Mittel ist ".$n->r2("mean0").".");
	function r2($col, $ref = 1){
		$s = "/^([a-zA-Z\-]{1,})([0-9]{1,})/";
		$c = preg_replace($s, "\\1", $col);
		$r = preg_replace($s, "\\2", $col);
		return $this->r($ref, $c, $r);
	}
	
	function rc($ref, $col, $row){
		$t = $this->t; $mx = recwert("select count(refna) from $t where refna <> ''") + 1;
		$refna = "rt".$mx;
		myq("insert into $t (refna, refto) values ('$refna', $ref)");
		$fe = import::mysql("select * from ".$t."final where refna = '$refna' or tabna <> ''");
		#show3($fe);
		for ($i = 0; $i < count($fe["lfn"]); ++$i) if ($fe["refna"][$i] == $refna) {
			$nr = $fe["tabnr"][$i + $ref];
			$s = recwert("select tabfe from ".$t."final where tabnr = '$nr'");
			$tfe = str::str2fe($s);
			return $tfe[array_keys($tfe)[$col]][$row];
		}
		return "<a><b>.....</b></a>";
	}

	function save(){
		myq("drop table if exists ".$this->t."final");
		myq("create table ".$this->t."final engine = myisam select * from $this->t");
		myq("delete from $this->t");
	}
}

// ______________________________________________________________ Inhaltsverzeichnis __________________________________________________________________________________
// voll funktionierendes Beispiel
//    inhaltsverzeichnis3(); dropt2b("ue");
//    ue(1, "Einleitung");
//    ue(2, "Zweite Übeschrift");  
// muß 2x aktualisiert werden
function inhaltsverzeichnis3(){
	$rs = myq("select ue, ueid, uenr, uetext from uefinal");
	$t1 = "<table border = 1><tr><td>";
	while($row = mysqli_fetch_row($rs)) {
		$nr = $row[0];
		$id = $row[1];
		$kn = $row[2];
		$te = $row[3];
		$t1 .= chr(10)."<p>".(lz(($nr - 1) * 5)."<a href = '#$id'>$te</a>")."</p>";
	}
	$t1 .= chr(10)."</td></tr></table>";
	echop($t1);
}

// _____________________________________________________________ Überschriften _____________________________________________________________________________________

function ue($nr, $te){
	$tb = "ue";
	$id = preg_replace("/ /", "_", $te);
	myq("create table if not exists ".$tb."      (lfn int auto_increment, ue int, ueid text, uenr text, uetext text, primary key (lfn)) engine = myisam");
	myq("create table if not exists ".$tb."final (lfn int auto_increment, ue int, ueid text, uenr text, uetext text, primary key (lfn)) engine = myisam");
	myq("insert into $tb (ue, ueid, uetext) values ($nr, '$id', '$te')");
	echo("<h$nr id = '".$id."' >$te</h$nr>");
}

function uesave(){
	myq("drop table if exists uefinal");
	myq("create table uefinal engine = myisam select * from ue");
	myq("delete from ue");
}

// _______________________________________________________________ Comments _________________________________________________________________________________________

// voll funktionierendes Beispiel
// 	ue(1, "Zusammenfassung");
// 		commshow(); dropt2b("^comms$");
// 	ue(1, "Ergebnisse");
// 		$fe = getrnd(10, 5);
// 		comm("In der Übersicht in ".tbref(1)." sieht man schöne Mittelwerte in normalem Bereich.");
// 		desc($fe, "c[1-5]");
// 		comm("Bei einem genaueren Blick in die ".tbref(-1)." sieht man auch, dass die Streuungen schwanken.");
// 		$fe = getrnd3(100, 5); comm("In der ".tbref(1)." zeigt sich ".r(1, "var1")." mit ".r(1, "mean21")." (SD = ".r(1, "sd21").")."); table::desc($fe, "c");
function comm($te, $ofi = ""){
	$te0 = $te;
	$te = preg_replace("/'/", "_h_", $te);
	$tb = "comm"; myq("create table if not exists $tb (lfn int auto_increment, comm text, primary key (lfn)) engine = myisam");
	myq("insert into $tb (comm) values ('$te')");
	echop($te0);
	if ($ofi !== "") write2($te0, ofi(currdb(), $ofi).".txt");
}

function commshow(){
	$tb = "comm"; 
	myq("create table if not exists ".$tb."      (lfn int auto_increment, comm text, primary key (lfn)) engine = myisam");
	myq("create table if not exists ".$tb."final (lfn int auto_increment, comm text, primary key (lfn)) engine = myisam");
	$rs = myq("select comm from commfinal");
	while($row = mysqli_fetch_row($rs)) $r .= preg_replace("/_h_/", "'", $row[0])." ";
	echop($r);
}

function commsave(){
	myq("drop table if exists commfinal");
	myq("create table commfinal engine = myisam select * from comm");
	myq("delete from comm");
}

// ________________________________________________________ auf Tabelleninhalte (p-Werte) verweisen (neu)  ________________________________________________________________

class tbref {
	function __construct() {
		$this->t = "tabs3";
		myq("create table if not exists $this->t (lfn int auto_increment, tabnr int, tabna text, refto int, r int, c int, id text, refna text, primary key (lfn))");
	}
	
	function register($na){
		$mx = recwert("select max(tabnr) from ".$this->t) + 1;
		if ($na !== "") myq("insert into $this->t (tabnr, tabna) values ($mx, '$na')");
	}	
	
	function r($nr, $id){
	}

}

// ______________________________________________________________ Zellzugriff auf Tabellen (alt) _________________________________________________________________________

//voll funktionierendes Beispiel
// dropt2b("^tabs");
// $tb = "daten01"; zufallsdaten4(10, 20, $tb); $fe = getmat("select * from ".$tb); comp($fe, "@c1@ = trunc(@c1@ / 0.25);"); comp($fe, "@c2@ = trunc(@c2@ / 0.25);");
// 
// #echo "Die nächste Tabelle zeigt für den Wert ".rc(1, 1, 0)." genau ".rc(1, 1, 2)."%.";
// #freq($fe, "c1$");
// #echo "Die obige Tabelle zeigt für den Wert ".rc(-1, 2, 0)." genau ".rc(-1, 2, 2)."%.";
// 
// echop("Die Tabelle zeigt für den Wert ".r(1, "c11")." insgesamt ".r(1, "percent1")."%.");
// freq($fe, "c1$");
// 
// freq($fe, "c2$");
// echop("Die obige Tabelle zeigt für den Wert ".r(-1, "c21")." insgesamt ".r(-1, "percent1")."%.");
// r_und_rc_fill();

function registertab($na){
	$tb = "tabs2";
	myq("create table if not exists $tb (lfn int auto_increment, tabnr int, tabna text, refto int, r int, c int, id text, refna text, primary key (lfn))");	
	$mx = recwert("select max(tabnr) from ".$tb) + 1;
	if ($na !== "") myq("insert into $tb (tabnr, tabna) values ($mx, '$na')");
}

function rc($ref, $r, $c){ //Reference auf eine Zeile und Spalte einer Tabelle
	$tb = "tabs2";
	myq("create table if not exists $tb (lfn int auto_increment, tabnr int, tabna text, refto int, r int, c int, id text, refna text, primary key (lfn))");
	$mx = recwert("select count(refna) from $tb where refna <> ''") + 1;
	$refna = "r".$mx;
	$te = "<b><font color = blue>...</font></b>";
	if ($ref !== "") {myq("insert into $tb (refna, refto, r, c) values ('$refna', $ref, $r, $c)"); return "<p2 class = $refna id = r".zufallsstring(5)." >$te</p2>"; }
}

// $fe = data::rnd(100, 5); for($i = 2; $i <= 5; ++$i) $fe = comp3($fe, "@k$i@ = trunc2(@c0$i@ / 30);");
// comm("In der nachfolgenden ".tbref(1)." zeigt sich ".r(1, "k30")." unter ".r(1, "k32")." mit ".r(1, "%2")."% besetzt (n = ".r(1,"n2").").");
// table::freq($fe, "k[3-5]$");
// comm("In der obigen ".tbref(-1)." zeigt sich ".r(-1, "k50")." unter ".r(-1, "k52")." mit ".r(-1, "%2")."% besetzt (n = ".r(-1,"n2").").");
function r($ref, $id){ //Reference auf eine Id einer Tabelle
	$tb = "tabs2";
	myq("create table if not exists $tb (lfn int auto_increment, tabnr int, tabna text, refto int, r int, c int, id text, refna text, primary key (lfn))");		
	$mx = recwert("select count(refna) from $tb where refna <> ''") + 1;
	$refna = "r".$mx;
	if ($ref !== "") {myq("insert into $tb (refna, refto, id) values ('$refna', $ref, '$id')"); return "<p2 class = $refna id = r".zufallsstring(5)." >...ref to $ref...</p2>"; }
}

function r_und_rc_fill(){
	$tb = "tabs2";
	myq("create table if not exists $tb (lfn int auto_increment, tabnr int, tabna text, refto int, r int, c int, id text, refna text, primary key (lfn))");
	$fe = get("select lfn, refto, r, c, id, refna from $tb where refto <> ''");
	for ($i = 1; $i < count($fe); ++$i) {
		$lfn   = $fe[$i][0];
		$refto = $fe[$i][1];
		$r     = $fe[$i][2];
		$c     = $fe[$i][3];
		$id    = $fe[$i][4]; 		
		$refna = $fe[$i][5];
		
		if ($refto > 0) {
			$fe2 = get("select tabna, tabnr from $tb where lfn > $lfn and tabna <>'' order by tabnr limit $refto");
			$tabna = $fe2[$refto][0];
		} else {
			$fe2 = get("select tabna, tabnr from $tb where lfn < $lfn and tabna <>'' order by lfn desc limit ".abs($refto));
			$tabna = $fe2[abs($refto)][0];
		}
		
		#für rc
		if ($id == "") echo "<script type = 'text/javascript'>
					d = document; tab = d.getElementById('$tabna'); e = d.getElementsByClassName('$refna');
					for(j = 0; j < e.length; j++) d.getElementById(e[j].id).innerHTML = tab.rows[$r].cells[$c].innerHTML.replace(/<b>|<\/b>/g, '');
			  	    </script>";
		
		#für r
		if ($id !== "") echo "<script type = 'text/javascript'>
					d = document; tab = d.getElementById('$tabna'); e = d.getElementsByClassName('$refna');
					td = tab.getElementsByTagName('td'); 
					for (var i = 0; i < td.length; i++) if (td[i].id == '$id') {
						for(j = 0; j < e.length; j++) d.getElementById(e[j].id).innerHTML = '<a href = #$tabna>' + td[i].innerHTML.replace(/<b>|<\/b>/g, '') + '</a>'; 
					}
				     </script>";
	}
}

// _____________________________________________ Referenz im Text setzen __________________________________________________________________________________________
// comm("Dies ist der ".ref::set("textmarke", "Anfang")." des Textes.");
// comm("Und nun ".ref::link("textmarke", "gehe zum Anfang")." des Textes.");
class ref {

	function set($name, $te, $col = "black") {
		return "<p2 style = 'color: $col;' id = '$name' >$te</p2>";
	}
	function link($na, $te) {
		return "<a href = '#$na'>$te</a>";
	}

}

// $fe = data::rnd(100, 2);
// $t = new table(); $t->desc($fe, "c");
// echop("Dies ist in obiger Tabelle in der ".pointer("ersten Spalte", -1, ".:0", "")." zu sehen.");
function pointer($te, $elnr, $rc = ".:.", $filter = "."){
	$id = "pointer_".zufallsstring(5);
	return "<a><table2 id = $id onmouseover = pointer(this.id,$elnr,1,'$rc','$filter') onmouseout = pointer(this.id,$elnr,0,'$rc','$filter')><tr><td>$te</td><tr></table2></a>";
}

// ______________________________________________________________ tabs einfacher _________________________________________________________________________________________

class button {

	function show($nr, $te = "Tab einfacher"){
		$n = "button_".zufallsstring(3);
		return "<button id = $n type = button onclick = hiderows($nr,this.id)>$te</button>";
	}

}

// _________________________________________________________________________________________________________________________________________________________________

//if (!defined('T_ML_COMMENT')) {define('T_ML_COMMENT', T_COMMENT);} else {define('T_DOC_COMMENT', T_ML_COMMENT);}
function strip_comments($source) {
	$tokens = token_get_all($source);
	$ret = "";
	foreach ($tokens as $token) {
		if (is_string($token)) 	$ret.= $token; else {
			list($id, $text) = $token;
			switch ($id) { 
			case T_COMMENT: 
			case T_ML_COMMENT: // we've defined this
			case T_DOC_COMMENT: // and this
				break;
			default:
				$ret.= $text;
				break;
			}
		}
	}    
	return trim(str_replace(array('<?','?>'),array('',''),$ret));
}

function currdb(){return recwert("select database()");}

function files_in_mysql($dirname){  //liest alle PDF-Artikel im Ordner literatur/quellen ein
	$tb = "literatur.files";
	myq("drop table ".$tb);
	myq("create table $tb (filename text, ordner int, datum varchar(12), fulltext(filename), fulltext(datum), lfn int, kuerzel text)"); err();

	$dir = opendir($dirname);
	while (false !== ($d = readdir($dir))){
			$n = fromto2($d,"_id","_");
			$k = fromto2($d,"_kue","_");
			if (is_numeric($n)) $lfn = $n; else $lfn = "null";
			$m = date ("Y-m-d", filemtime($dirname."/".$d));
			if (is_dir($dirname."/".$d)) $ordner = 1; else $ordner = 0;
			if ($d!=="." and $d!=="..") myq("insert into $tb (filename, ordner, datum, lfn, kuerzel) values ('$d', $ordner, '$m', $lfn, '$k') ");
	}
	closedir($dir);
	myq("alter table $tb order by filename, kuerzel");
}

function lz($zahl){
    $l = "";
    for ($i = 1; $i <= $zahl; ++$i) $l = $l."&nbsp;";
    return $l;
}

function properties($eigenschaft, $wert, $del = 1){
	$t = "properties";	
	myq("create table if not exists properties (eigenschaft text, wert text) engine = myisam");
	#myq("alter table properties add fulltext eigenschaft_index (eigenschaft)");	
	if ($del == 1) myq("delete from $t where lower(eigenschaft)=lower('$eigenschaft') ");
	myq("insert into $t (eigenschaft,wert) values ('$eigenschaft','$wert')");
}

function properties2($eigenschaft, $wert, $db){
	$t = $db.".properties";
	myq("create table if not exists $t (eigenschaft text, wert text) engine = myisam");
	myq("delete from $t where lower(eigenschaft) = lower('$eigenschaft') ");
	myq("insert into $t (eigenschaft,wert) values ('$eigenschaft','$wert')");
}

function saveproperty($eigenschaft, $wert){
	$t = "properties";	
	myq("create table if not exists properties (eigenschaft text, wert text) engine = myisam");
	myq("delete from $t where lower(eigenschaft) = lower('$eigenschaft') ");
	myq("insert into $t (eigenschaft,wert) values ('$eigenschaft','$wert')");
}

function getproperty($wert){
	myq("create table if not exists properties (eigenschaft text, wert text) ");
	return recwert("select wert from properties where eigenschaft = '$wert'");
}

function getproperty2($wert, $db){
	$t = $db.".properties";
	myq("create table if not exists $t (eigenschaft text, wert text) ");
	return recwert("select wert from $t where eigenschaft = '$wert'");
}

function mw($spr = "d") {
	if ($spr == "d") return "statistischer Test: Mann-Whitney-U-test (Bortz, 2005; Lehmann, 1998), p-Werte <u><</u> 0.05 k&ouml;nnen als explorativ signifikant bezeichnet werden, 
				(vergleiche Mittel- oder Medianwerte).
				Der sogenannte Shift ist ein Kontrastma&szlig; (Hodges-Lehmann estimator of shift, Lehmann, 1998, CI = ist der Vertrauensbereich, confidence range).
				Es beschreibt die Verschiebung zweier Gruppen gegeneinander und ist statistisch am effizientesten. Als Maß für die Genauigkeit der Verschiebung ist Konfidenzband angegeben
				(vergleiche deskriptiv auch die Mittelwerte).";
	else return "test = Mann-Whitney-U-test (Bortz, 2005, consider descriptive p-value as significant if p <u><</u> 0.05, then compare means or medians), 
				shift = contrast measure (Hodges-Lehmann estimator of shift, Lehmann, 1998, and CI = confidence range) ";
}

function od($spr = "d") {
	if ($spr == "d") return "n = Faelle, p-Wert ist das Ergebnis des Fisher-Tests (Bortz, 2005; Sachs, 2003). 
				Er kann als exploratorisch signifikant bezeichnet werden, wenn p <u><</u> 0.05, 
				(vergleiche die jeweiligen Prozente innerhalb einer Zeile), 
				OR = Odds Ratio (nur angegeben bei Vierfeldertafeln), CI = 95%-Konfidenz-Interval der Odds Ratio (d.h. untere und obere Grenze).";
	else return "n = cases, p-value = Fisher-Test (Bortz, 1990; Sachs, 2003, exploratory significant if p <u><</u> 0.05, compare percentages within rows), 
				OR = odds ratio (given only if tables are 2-by-2), CI = 95%-confidence interval of odds ratio (lower and upper limit of OR).";
}

function lgr($spr = "d"){
	return "wichtigster Kennwert: p-Wert (explor. signif. wenn <u><</u> 0.05) und Odds Ratio (ein Maß für Risiko, OR und das Konfidenzband, lower and upper CI = confidence interval), 
		weniger wichtig sind: coeff = Koeffizient der Modellgleichung, se = standard error, z = Prüfstatistik";
}

function kmtext(){
	return "Statistical analyses were performed using SPSS software, Version 21, and the R statistical package (http://cran.r-project.org). 
		Descriptively means and standard deviations were calculated for continuous data, frequencies and percentags (e.g. incidence rates) for discontinuous data.
		Fisher exact test were used to determine the significance of any differences between inzidence rates. 
		Mann-Whitney-test were calculated to show differences in continuous data.
		Survival curves were plotted using the Kaplan-Meier method (Kaplan & Meier, 1958). Curves of different groups were compared based on the log-rank test.
		Hazard ratios and 95% confidence intervals were calculated using univariate Cox proportional-hazards models. 
		Correlation coefficients according to Pearson were calculated to explore associations between continuous data.
		In the multivariate model, we included terms that appeared to be important on univariate analyses and then used a backward selection 
		method to remove the nonsignificant terms from the model. A P value less equal 0.05 (2-sided) was considered statistically significant.";
}

function kmtext2(){
	return " Methode nach Kaplan & Meier (1958; Cox, 1984). Kumulative Inzidenzraten (100% minus survival, y-Achse) über die Zeit (x-Achse). Statistischer Test auf generellen Unterschied: Log-Rank-Test (Cox, 1984). p-Werte <u><</u> 0.05 verweisen auf einen signifikanten Unterschied.";
}

function statistischemethoden(){
	#ue(1, "Statistische Methoden");
	echop("Im Rahmen der vorliegenden Analysen wurden - je nach Fragestellung - die folgenden Kennwerte angegeben:
		Bei Häufigkeitsdaten waren das absolute und relative Häufigkeiten (% Werte), 
		bei metrischen Daten das arithmetische Mittel, als Maß für Variabilität die Standardabweichung, 
		das Minimum und Maximum, die Fallzahl, sowie die Perzentile (sofern nötig).
		Als Software wurde R in der Version 3.4 eingesetzt (<a target = blank_ href = 'http://www.R-project.org'>www.R-project.org</a>). 
		p-Werte <u><</u> 0.05 verweisen stets auf einen explorativ signifikanten Befund.
		Es wurde eine Reihe statistischer Tests eingesetzt, um Unterschiede explorativ prüfen zu können. 
		Schwerpunkt der Testungen waren im Wesentlichen voraussetzungsfreie Tests (Fisher-Test, oder Rangtests wie der Mann-Whitney-U-Test, Lehmann, 1998).
		Bei diesen Tests muss nicht aufwendig belegt werden (strenggenommen aus der Literatur, d.h. aus Daten der interessierenden Population), 
		ob die Daten normalverteilt und die Varianzen homogen sind (dies alles sind nötige Voraussetzungen bei der Varianzanalyse, ANOVA). Beide zuvor genannten Voraussetzungen sind bei den eingesetzten Tests nicht nötig.
		Ohne Ausnahme werden all diese Tests rein explorativ eingesetzt. Insofern haben alle Befunde keinen „beweisenden“ oder „konfirmativen“ Charakter.
		Um Zweigruppen-Unterschiede auf explorative Signifikanz zu prüfen, wurde der Mann-Whitney-U-Test (Lehmann, 1998) eingesetzt, er testet Unterschiede zweier Gruppen in Parametern, die stetige Daten enthalten.
		Sofern Dreigruppen-Unterschiede interessierten, wurde die Rangvarianzanalyse nach Kruskal & Wallis eingesetzt (Lehmann, 1998).
		Mittels Chi²-Test und dem Fisher-Yates-Test (Bortz, 2010) wurden Unterschiede bei Häufigkeitsdaten geprüft (z.B. Prüfung, ob Unterschiede in Prozentwerten bestehen).
		Deskriptiv werden (allerdings nur bei 2 x 2 Tafeln) Odds-Ratios und deren 95%-Konfidenzintervallgrenzen ('upper' und 'lower') angegeben. Bei größeren Tafeln (z.B. 2 x 3) können nur die p-Werte angegeben werden,
		da die Odds-Ratio nur für 2 x 2 - Tafeln definiert ist.
	");
		#Die logistische Regression (Agresti, 2007) wurde als Prädiktionsverfahren eingesetzt zur Prüfung von Zusammenhängen aus mehreren Prädiktoren zu einem binären Wert (ja versus nein bzw. vorhanden versus nicht-vorhanden).
		
	/*
	Der Korrelationskoeffizient nach Pearson oder nonparametrisch nach Spearman (Lehmann, 1998) wurde als Zusammenhangsmaß eingesetzt, wenn die Daten keinen strengen Verteilungsvoraussetzungen wie z.B. 
		Normalverteilung entsprechen. Eine bedeutsame Korrelation (p<0.05) über 0.5 kann als moderat, über 0.7 als 
		gut und ab 0.9 als optimaler Zusammenhang interpretiert werden. Eine negative Korrelation verweist auf einen gegenläufigen („größer-kleiner“) Zusammenhang, eine 
		positive Korrelation auf einen gleichläufigen („größer-größer“) Zusammenhang.
	echop("Im Rahmen der vorliegenden Analysen wurden - je nach Fragestellung - die folgenden Kennwerte angegeben:
		Bei Häufigkeitsdaten waren das absolute und relative Häufigkeiten (% Werte), 
		bei metrischen Daten das arithmetische Mittel, als Maß für Variabilität die Standardabweichung, 
		das Minimum und Maximum, die Fallzahl, sowie die Perzentile (sofern nötig).
		Als Software wurde R in der Version 3.2 eingesetzt (<a target = blank_ href = 'http://www.R-project.org'>www.R-project.org</a>). 
		p-Werte <u><</u> 0.05 verweisen hier auf einen explorativ signifikanten Befund.
		Es wurde eine Reihe statistischer Tests eingesetzt. Schwerpunkt der Testungen waren im Wesentlichen voraussetzungsfreie Tests (Fisher-Test, oder Rangtests wie der Mann-Whitney-U-Test, Lehmann, 1998).
		Bei diesen Tests muss nicht aufwendig belegt werden (strenggenommen aus der Literatur, d.h. aus Daten der interessierenden Population), 
		ob die Daten normalverteilt und die Varianzen homogen sind (nötige Voraussetzungen bei der Varianzanalyse, ANOVA), denn beide Voraussetzungen sind bei den eingesetzten Tests nicht nötig.
		Ohne Ausnahme werden all diese Tests rein explorativ eingesetzt. Insofern haben alle Befunde keinen „beweisenden“ oder „konfirmativen“ Charakter.
		Um Zweigruppen-Unterschiede auf explorative Signifikanz zu prüfen, wurde der Mann-Whitney-U-Test (Lehmann, 1998) eingesetzt, er testet Unterschiede zweier Gruppen in Parametern, die stetige Daten enthalten.
		Sofern Dreigruppen-Unterschiede interessierten, wurde die Rangvarianzanalyse nach Kruskal & Wallis eingesetzt (Lehmann, 1998).
		Mittels Chi²-Test und dem Fisher-Yates-Test (Bortz, 2010) wurden Unterschiede bei Häufigkeitsdaten geprüft (z.B. Prüfung, ob Unterschiede in Prozentwerten bestehen).
		Die multiple Regression wurde verwendet, um Prädiktion eines Parameters (Zielvariable) anhand mehrerer Prädiktoren (Bortz, 2010) zu ermitteln. 
		Die Regessions-Befunde werden stets um eine Vorausetzungsprüfung (Normalverteilung und homogene Verteilung der Fehlerwerte) ergänzt.
		Die logistische Regression (Agresti, 2007) wurde als Prädiktionsverfahren eingesetzt zur Prüfung von Zusammenhängen aus mehreren Prädiktoren zu einem binären Wert (ja versus nein bzw. vorhanden versus nicht-vorhanden).
	");
	//*/
}

function litliste(){
	$fe = array(
		"Agresti A. Categorical Data Analysis. Wiley (2007)",
		"Bortz J, Lienert GA, Boehnke K. Verteilungsfreie Methoden in der Biostatistik. Springer (2008)",
		"Bortz J, Schuster C. Statistik fuer Sozialwissenschaftler. Springer (2010)",
		"Lehmann EL. Nonparametrics - Statistical methods based on ranks. Prentice Hall, New Jersey (1998)",
		"R Core Team. R: A language and environment for statistical computing. R Foundation for Statistical Computing. Vienna, Austria (2012)"
		);
	for ($i = 0; $i < count($fe); ++$i) echop($fe[$i]);
}


//echo 2 * (1 - cumnormdist(1.96)); // = p-Wert 0.04999
function cumnormdist($x){
	$x = abs($x);
	$b1 =  0.319381530; $b2 = -0.356563782; $b3 =  1.781477937; $b4 = -1.821255978; $b5 =  1.330274429; $p  =  0.2316419; $c  =  0.39894228;
	if($x >= 0.0) {
		$t = 1.0 / ( 1.0 + $p * $x );
		return (1.0 - $c * exp( -$x * $x / 2.0 ) * $t *
		( $t *( $t * ( $t * ( $t * $b5 + $b4 ) + $b3 ) + $b2 ) + $b1 ));
	} else {
		$t = 1.0 / ( 1.0 - $p * $x );
		return ( $c * exp( -$x * $x / 2.0 ) * $t *
		( $t *( $t * ( $t * ( $t * $b5 + $b4 ) + $b3 ) + $b2 ) + $b1 ));
	}
}

// echo echop(norminv(0.975));  // = z-Wert von 1.96
function norminv($p){
	$a1 = -39.6968302866538; $a2 = 220.946098424521; $a3 = -275.928510446969;
	$a4 =  138.357751867269; $a5 = -30.6647980661472; $a6 = 2.50662827745924;
	$b1 = -54.4760987982241; $b2 = 161.585836858041; $b3 = -155.698979859887;
	$b4 =  66.8013118877197; $b5 = -13.2806815528857; $c1 = -7.78489400243029E-03;
	$c2 = -0.322396458041136; $c3 = -2.40075827716184; $c4 = -2.54973253934373;
	$c5 =  4.37466414146497; $c6 = 2.93816398269878; $d1 = 7.78469570904146E-03;
	$d2 =  0.32246712907004; $d3 = 2.445134137143; $d4 = 3.75440866190742;
	$p_low = 0.02425; $p_high = 1 - $p_low;
	$q = 0.0; $r = 0.0;
	if($p < 0 || $p > 1){
		throw new Exception("NormSInv: Argument out of range.");
	} else if($p < $p_low){
		$q = pow(-2 * log($p), 2);
		$NormSInv = ((((($c1 * $q + $c2) * $q + $c3) * $q + $c4) * $q + $c5) * $q + $c6) / (((($d1 * $q + $d2) * $q + $d3) * $q + $d4) * $q + 1);
	} else if($p <= $p_high){
		$q = $p - 0.5; $r = $q * $q;
		$NormSInv = ((((($a1 * $r + $a2) * $r + $a3) * $r + $a4) * $r + $a5) * $r + $a6) * $q / ((((($b1 * $r + $b2) * $r + $b3) * $r + $b4) * $r + $b5) * $r + 1);
	} else {
		$q = pow(-2 * log(1 - $p), 2);
		$NormSInv = -((((($c1 * $q + $c2) * $q + $c3) * $q + $c4) * $q + $c5) * $q + $c6) / (((($d1 * $q + $d2) * $q + $d3) * $q + $d4) * $q + 1);
	}
	return $NormSInv;
}

function textin2dimfeld($t1, $t2){
	$fe1 = explode(",", $t1);
	$fe2 = explode(",", $t2);
	$fe[0][0] = "uv"; $fe[0][1] = "av";
	for ($i = 1; $i < count($fe1); ++$i){$fe[$i][0] = $fe1[$i]; $fe[$i][1] = $fe2[$i];}
	return $fe;
}

function sign($p, $nk){
    $p = round($p, $nk);
    $t = Format2($p, "0.00000000000000000");
    $t = mid($t, 1, 2 + $nk);
    if ($t == 0) return "p < " . mid($t, 1, strLen($t) - 1) . "1"; else return "p = " . $t;
}

function orfo($or, $nk = 1){
	return round(($or - 1) * 100, $nk)." %";
}

// $fe = array(array( 10, -15,  30,   6, -8 ), array(  0,  -4,  60,  11, -5 ), array(  8,   9,   2,   3,  7 ), array( 25,  10,  -9,   9,  0 ), array( 13,   3, -12,   5,  2 ));
// $s = new show(); $fe3 = matrixmult($fe, $fe); $s->fe0($fe3);
function matrixmult($m1, $m2){
	$r = count($m1);
	$c = count($m2[0]);
	$p = count($m2);
	if(count($m1[0]) != $p){throw new Exception('Incompatible matrixes');}
	$m3 = array();
	for ($i = 0; $i < $r; $i++){
		for($j = 0; $j < $c; $j++){
			$m3[$i][$j] = 0;
			for($k = 0; $k < $p; $k++){
				$m3[$i][$j] += $m1[$i][$k] * $m2[$k][$j];
			}
		}
	}
	return $m3;
}

function matrixtransp($m){
	$r = count($m);
	$c = count($m[0]);
	$mt = array();
	for($i = 0; $i < $r; $i++){
		for($j = 0; $j < $c; $j++) $mt[$j][$i] = $m[$i][$j];
	}
	return $mt;
}

// $fe = array(array( 10, -15,  30,   6, -8 ), array(  0,  -4,  60,  11, -5 ), array(  8,   9,   2,   3,  7 ), array( 25,  10,  -9,   9,  0 ), array( 13,   3, -12,   5,  2 ));
// $fe2 = matrixinvert($fe); $s = new show(); $s->fe0($fe); $s->fe0($fe2);
function matrixinvert($A){
	$n = count($A);
	$I = identity_matrix($n);
	for ($i = 0; $i < $n; ++ $i) $A[$i] = array_merge($A[$i], $I[$i]);
	for ($j = 0; $j < $n-1; ++ $j) {
		for ($i = $j+1; $i < $n; ++ $i) {
			if ($A[$i][$j] !== 0) {
				$scalar = $A[$j][$j] / $A[$i][$j];
				for ($jj = $j; $jj < $n*2; ++ $jj) {
					$A[$i][$jj] *= $scalar;
					$A[$i][$jj] -= $A[$j][$jj];
				}
			}
		}
	}
	for ($j = $n-1; $j > 0; -- $j) {
		for ($i = $j-1; $i >= 0; -- $i) {
			if ($A[$i][$j] !== 0) {
				$scalar = $A[$j][$j] / $A[$i][$j];
				for ($jj = $i; $jj < $n*2; ++ $jj) {
					$A[$i][$jj] *= $scalar;
					$A[$i][$jj] -= $A[$j][$jj];
				}
			}
		}
	}
	for ($j = 0; $j < $n; ++ $j) {
		if ($A[$j][$j] !== 1) {
			$scalar = 1 / $A[$j][$j];
			for ($jj = $j; $jj < $n*2; ++ $jj) {
				$A[$j][$jj] *= $scalar;
			}
		}
	}
	$Inv = array();
	for ($i = 0; $i < $n; ++ $i) $Inv[$i] = array_slice($A[$i], $n);
	return $Inv;
}

function identity_matrix($n){
	$I = array();
	for ($i = 0; $i < $n; ++ $i) {
		for ($j = 0; $j < $n; ++ $j) $I[$i][$j] = ($i == $j) ? 1 : 0;
	}
	return $I;
}

function savebooks($fe){
	$t = "books";
	#myq("drop table if exists $t");
	#myq("create table if not exists $t (book text, booknr int primary key auto_increment not null) engine = mysql");
	#myq("create unique index book_index on $t (book(50));");
	for($i = 0; $i < count($fe); ++$i) {$b = $fe[$i]; myq("insert into $t (book) values ('$b') on duplicate key update book = '$b'"); }
}

function savepages($b, $fe){
	$t = "pages";
	#myq("drop table if exists $t");
	#myq("create table if not exists $t (book text, booknr int, page text, pagenr int auto_increment not null, primary key (booknr, pagenr)) engine = mysql");
	#myq("create unique index book_page_index on $t (book(50), page(50));");
	$booknr = recwert("select booknr from books where book = '$b'");
	for($i = 0; $i < count($fe); ++$i) {$p = $fe[$i]; myq("insert into $t (book, booknr, page) values ('$b', '$booknr', '$p') on duplicate key update book = '$b'"); }
}

function readbooks($p){
	$di = opendir($p."."); while($d = readdir($di)) if (is_dir($d) and $d !== "." and $d !== ".." and !preg_match("/^\./", $d)) $fe1[] = $d; closedir($di); //savebooks($fe1);
	$t = "books_combined";
	myq("create table if not exists $t (book text) engine = mysql");
	$rs = myq("select book from $t"); while($row = mysql_fetch_row($rs)) $fe1[] = $row[0]; sort($fe1); $_SESSION['fe1'] = $fe1;
	return $fe1;
}

function readpages($p, $book){
	$di = opendir($p."/".$book); $z = -1; while($d = readdir($di)) if (!is_dir($d) and $d !== "." and $d !== ".." and instr($d, ".jpg")) $fe2[] = $d; sort($fe2); closedir($di); //savepages($book, $fe2);
	return $fe2;
}

function stattable(){
	$tt[0][0] = "Statistiker"     ; $tt[0][1] = "Dr. rer. nat. Ulrich Stefenelli";
	$tt[1][0] = "Software"        ; $tt[1][1] = "R";
	$tt[2][0] = "Datenbank"       ; $tt[2][1] = "MySQL";
	$tt[3][0] = "Programmroutine" ; $tt[3][1] = "PHP";
	$tt[4][0] = "Webserver"       ; $tt[4][1] = "Apache 2";
	$tt[5][0] = "Report in"	      ; $tt[5][1] = "HTML + CSS";
	show($tt, "1", "150,250");
}

function haeufig(){
	// 	sehr häufig	> 10 %
	// 	häufig	 	1 - 10 %
	// 	gelegentlich	0,1 - 1 %
	// 	selten		0,01 - 0,1 %
	// 	sehr selten	< 0,01 %
}

function ods($fe){
	$l = chr(10);
	exec("rm -r /tmp/ods");
	exec("mkdir /tmp/ods");
	exec("mkdir /tmp/ods/META-INF");

	$t = "<?xml version='1.0' encoding='UTF-8'?>
		<manifest:manifest xmlns:manifest='urn:oasis:names:tc:opendocument:xmlns:manifest:1.0' manifest:version='1.2'>
		<manifest:file-entry manifest:full-path='/' manifest:version='1.2' manifest:media-type='application/vnd.oasis.opendocument.spreadsheet'/>
		<manifest:file-entry manifest:full-path='content.xml' manifest:media-type='text/xml'/>
		</manifest:manifest>";
	write2($t, $o="/tmp/ods/META-INF/manifest.xml");
	
	$ce = "<style:paragraph-properties fo:text-align='center'/>";
	$bo = "<style:text-properties fo:font-weight='bold'/>";
	
	$t = "<?xml version='1.0' encoding='UTF-8'?>
	<office:document-content 
		xmlns:office='urn:oasis:names:tc:opendocument:xmlns:office:1.0' 
		xmlns:style='urn:oasis:names:tc:opendocument:xmlns:style:1.0' 
		xmlns:text='urn:oasis:names:tc:opendocument:xmlns:text:1.0' 
		xmlns:table='urn:oasis:names:tc:opendocument:xmlns:table:1.0' 
		xmlns:fo='urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0' 
		xmlns:script='urn:oasis:names:tc:opendocument:xmlns:script:1.0' office:version='1.2'>

	<office:font-face-decls></office:font-face-decls>
	<office:automatic-styles>
		<style:style style:name='grau1' style:family='table-cell'>
			<style:table-cell-properties fo:border-bottom='0.06pt solid #000000' fo:border-top='0.06pt solid #000000' fo:background-color='#eeeeee' /> $bo $ce
		</style:style>
		<style:style style:name='grau2' style:family='table-cell'>
			<style:table-cell-properties fo:border-bottom='0.06pt dotted #c0c0c0' fo:background-color='#eeeeee' /> $ce
		</style:style>
		<style:style style:name='grau3' style:family='table-cell'>
			<style:table-cell-properties fo:border-bottom='0.06pt solid #000000' fo:background-color='#eeeeee' /> $ce
		</style:style>

	</office:automatic-styles>

	<office:body>
		<office:spreadsheet>
			<table:table table:name='Tab1'>".$l;
			for ($i = 0; $i < count($fe); ++$i){
				$t .= "<table:table-row>".$l;
				for ($j = 0; $j < count($fe[0]); ++$j){
					if ($i == 0) $st = "grau1";
					if ($i >  0) $st = "grau2";
					if ($i == count($fe) - 1) $st = "grau3";
					
					$t .= "<table:table-cell table:style-name='$st'><text:p>".$fe[$i][$j]."</text:p></table:table-cell>".$l;
				}
				$t .= "</table:table-row>".$l;
			}
	$t .= "		</table:table><table:named-expressions/>
		</office:spreadsheet>
	</office:body>
	</office:document-content>";
	write2($t, $o="/tmp/ods/content.xml");
	
	$p = "/tmp/ods";
 	exec("7za a $p/zz.ods $p/*;");
	shell_exec("/usr/lib/libreoffice/program/soffice.bin --writer --nologo --norestore  --nolockcheck $p/zz.ods  >/dev/null 1>&1 &");
}

function cs($c){return "colspan = $c align = center valign = middle"; }

function sqlite_test(){
	/*
	$db = new SQLite3('/tmp/tmp.db'); $tb = "cc";
	#$db->exec("drop table $tb");
	$db->exec("create table $tb (nr int, a int, b int)");
	$db->exec("create index myind on $tb (a,b);");
	for ($i = 0; $i < 10; ++$i) $db->exec("insert into $tb (nr, a,b) values ($i, ".mt_rand(1, 10).", ".mt_rand(1, 10).");");
	
	$rs = $db->query("select a,b from $tb");
	$t = "<table border = 1>";
	while ($row = $rs->fetchArray()) {var_dump($row); $t .= "<tr><td>".implode("</td><td>", $row)."</td></tr>";}
	$t .= "</table>";
	echop($t);
	//*/
	
	$db = new SQLite3('/tmp/tmp.db'); $tb = "cc";
	$rs = $db->query("select a,b from $tb");
	$t = "<table border = 1>";
	while ($row = $rs->fetchArray()) {var_dump($row); $t .= "<tr><td>".implode("</td><td>", $row)."</td></tr>";}
	$t .= "</table>";
	echop($t);

}

function ip(){
	return exec("/sbin/ifconfig eth0 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'");
}

//if (preg_match('/192\.168\.178\./', ip2())) echo "";

function ip2($ip = null, $deep_detect = TRUE){
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'      ], FILTER_VALIDATE_IP)) $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }
    return $ip;
}

function round100($n){
	if ($n < 0) $n = floor($n/100) * 100; else $n = ceil($n/100) * 100;
	return $n;
}

function col2($n){
	if ($n < 0) $fa = "red"; else $fa = "blue";
	return "<p2 style = 'color: $fa'>$n</p2>";
}

function col($n){
	$a = explode(" ", $n);
	if ($a[0] < 0) $fa = "red"; else $fa = "blue";
	return "<p2 style = 'color: $fa'>$n</p2>";
}

function gl(){ // geschütztes Leerzeichen
	return "&nbsp;";
}

// foreach($_POST as $item) {$_POST[$item] = t2($_POST[$item]);}
function t2($s){return preg_replace("/[^a-züöäß,A-ZÜÖÄ0-9;\/ \-\.@]/", "", $s);}

// echo(ref("Der Name ist ref[1;1] und die Strasse ist ref[1;2]."));
function ref($s, $kunde){
	$s = preg_replace_callback(
		"/ref\[(\d);(\d)\]/", 
		function ($m) {
			global $kunde;
			$m[0] = preg_replace("/ref\[|\]/", "", $m[0]);
			$f = fromto($m[0], "", ";");
			$l = fromto($m[0], ";", "");
			$m[0] = recwert("select wert from daten where kunde = $kunde and form = $f and lfn = $l");
			return $m[0];
		},
		$s  );
	return $s;
}

function log22($t, $fi){
	fwrite(fopen($fi,"a"),$t);
}

function he($fe, $n = 10, $wi = "80"){
	if ($n == 0) $n = 9999999;
	md("n = ".nrow($fe));
	show3(data::head($fe, $n), "2", "80");
}

function nrow($fe){
	$kf = array_keys($fe);
	return count($fe[$kf[0]]);
}

function nu($w){
	return is_numeric($w);
}

?>
