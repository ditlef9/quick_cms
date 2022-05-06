<?php
/**
*
* File: _admin/common/functions/latex.php
* Version 23:08 21.10.2011
* Copyright (c) 2010 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Ver 1.0
*
*/

/* Usage --------------------------------------------------------------------- */
// 
// Just use latex('latex');
// 
// Example: latex('R= /frac VI');
//
// Good luck
// Sindre Ditlefsen
// w3senteret.com



function latex($formula){


	/*- Storedir ----------------------------------------------------------------- */
	$documentRoot = $_SERVER['DOCUMENT_ROOT'];
	// Check for / at end
	$check = substr($documentRoot, -1);
	if($check != "/"){ $documentRoot = $documentRoot . "/"; }
	$storeDir     = "$documentRoot" . "_latex/imgs";



	/*- Sources ------------------------------------------------------------------ */
	$SourceURL[0] = "http://chart.apis.google.com/chart?cht=tx&chl=";
	$SourceURL[1] = "http://l.wordpress.com/latex.php?bg=ffffff&fg=000000&s=1.0&latex=";


	/*- ScriptStart -------------------------------------------------------------- */
	$request_uri = $_SERVER["REQUEST_URI"]; // /w3senteret/_latex/latex.php?V=R*i
	$php_self    = $_SERVER['PHP_SELF'];	// /w3senteret/_latex/latex.php


	// Replace words
	$formulaUrl = str_replace("$php_self?", "", $request_uri);
	$formulaUrl = $formula;
	$formulaUrl = str_replace('/sum', '\sum', $formulaUrl);
	$formulaUrl = str_replace('\Sum', '/sum', $formulaUrl);
	$formulaUrl = str_replace('/frac', '\frac', $formulaUrl);
	$formulaUrl = str_replace('+', '%2B', $formulaUrl);
	$formulaUrl = str_replace('/vspace', '\vspace', $formulaUrl);
	$formulaUrl = str_replace('/em', '\bf', $formulaUrl);
	$formulaUrl = str_replace('/color', '\color', $formulaUrl);
	$formulaUrl = str_replace('*', ' \cdot ', $formulaUrl);

	$formulaUrl = str_replace('/rightarrow', '\rightarrow', $formulaUrl);
	$formulaUrl = str_replace('/underline', '\underline', $formulaUrl);
	$formulaUrl = str_replace('/sqrt', '\sqrt', $formulaUrl);
	$formulaUrl = str_replace('/displaystyle', '\displaystyle', $formulaUrl);
	$formulaUrl = str_replace('/lim', '\lim', $formulaUrl);
	$formulaUrl = str_replace('/oint', '\oint', $formulaUrl);
	$formulaUrl = str_replace('/choose', '\choose', $formulaUrl);


	$formulaUrl = str_replace('/deg', '^\circ', $formulaUrl);
	$formulaUrl = str_replace('/pi', '\pi', $formulaUrl);
	$formulaUrl = str_replace('/mathcal', '\mathcal', $formulaUrl);

	// Operators
	$formulaUrl = str_replace('+-', '\pm', $formulaUrl);
	$formulaUrl = str_replace('/pm', '\pm', $formulaUrl);
	$formulaUrl = str_replace('/Box', '\Box', $formulaUrl);
	$formulaUrl = str_replace('/Diamond', '\Diamond', $formulaUrl);
	$formulaUrl = str_replace('/amalg', '\amalg', $formulaUrl);
	$formulaUrl = str_replace('/ast', '\ast', $formulaUrl);
	$formulaUrl = str_replace('/bigcirc', '\bigcirc', $formulaUrl);
	$formulaUrl = str_replace('/bigtriangledown', '\bigtriangledown', $formulaUrl);
	$formulaUrl = str_replace('/bigtriangleup', '\bigtriangleup', $formulaUrl);
	$formulaUrl = str_replace('/bullet', '\bullet', $formulaUrl);
	$formulaUrl = str_replace('/cap', '\cap', $formulaUrl);
	$formulaUrl = str_replace('/cdot', '\cdot', $formulaUrl);
	$formulaUrl = str_replace('/circ', '\circ', $formulaUrl);
	$formulaUrl = str_replace('/cup', '\cup', $formulaUrl);
	$formulaUrl = str_replace('/dagger', '\dagger', $formulaUrl);
	$formulaUrl = str_replace('/ddagger', '\ddagger', $formulaUrl);
	$formulaUrl = str_replace('/diamond', '\diamond', $formulaUrl);
	$formulaUrl = str_replace('/div', '\div', $formulaUrl);
	$formulaUrl = str_replace('/lhd', '\lhd', $formulaUrl);
	$formulaUrl = str_replace('/mp', '\mp', $formulaUrl);
	$formulaUrl = str_replace('/odot', '\odot', $formulaUrl);
	$formulaUrl = str_replace('/ominus', '\ominus', $formulaUrl);
	$formulaUrl = str_replace('/oplus', '\oplus', $formulaUrl);
	$formulaUrl = str_replace('/oslash', '\oslash', $formulaUrl);
	$formulaUrl = str_replace('/otimes', '\otimes', $formulaUrl);
	$formulaUrl = str_replace('/rhd', '\rhd', $formulaUrl);
	$formulaUrl = str_replace('/setminus', '\setminus', $formulaUrl);
	$formulaUrl = str_replace('/sqcap', '\sqcap', $formulaUrl);
	$formulaUrl = str_replace('/sqcup', '\sqcup', $formulaUrl);
	$formulaUrl = str_replace('/star', '\star', $formulaUrl);
	$formulaUrl = str_replace('/times', '\times', $formulaUrl);
	$formulaUrl = str_replace('/triangleleft', '\triangleleft', $formulaUrl);
	$formulaUrl = str_replace('/triangleright', '\triangleright', $formulaUrl);
	$formulaUrl = str_replace('/unlhd', '\unlhd', $formulaUrl);
	$formulaUrl = str_replace('/unrhd', '\unrhd', $formulaUrl);
	$formulaUrl = str_replace('/uplus', '\uplus', $formulaUrl);
	$formulaUrl = str_replace('/vee', '\vee', $formulaUrl);
	$formulaUrl = str_replace('/wedge', '\wedge', $formulaUrl);
	$formulaUrl = str_replace('/wr', '\wr', $formulaUrl);

	// Relations
	$formulaUrl = str_replace('/approx', '\approx', $formulaUrl);
	$formulaUrl = str_replace('/asymp', '\asymp', $formulaUrl);
	$formulaUrl = str_replace('/bowtie', '\bowtie', $formulaUrl);
	$formulaUrl = str_replace('/cong', '\cong', $formulaUrl);
	$formulaUrl = str_replace('/dashhv', '\dashhv', $formulaUrl);
	$formulaUrl = str_replace('/dotteq', '\dotteq', $formulaUrl);
	$formulaUrl = str_replace('/equiv', '\equiv', $formulaUrl);
	$formulaUrl = str_replace('/frown', '\frown', $formulaUrl);
	$formulaUrl = str_replace('/ge', '\ge', $formulaUrl);
	$formulaUrl = str_replace('/gg', '\gg', $formulaUrl);
	$formulaUrl = str_replace('/in', '\in', $formulaUrl);
	$formulaUrl = str_replace('/le', '\le', $formulaUrl);
	$formulaUrl = str_replace('/ll', '\ll', $formulaUrl);
	$formulaUrl = str_replace('/mid', '\mid', $formulaUrl);
	$formulaUrl = str_replace('/models', '\models', $formulaUrl);
	$formulaUrl = str_replace('/neq', '\neq', $formulaUrl);
	$formulaUrl = str_replace('/ni', '\ni', $formulaUrl);
	$formulaUrl = str_replace('/parallel', '\parallel', $formulaUrl);
	$formulaUrl = str_replace('/perp', '\perp', $formulaUrl);
	$formulaUrl = str_replace('/prec', '\prec', $formulaUrl);
	$formulaUrl = str_replace('/preceq', '\preceq', $formulaUrl);
	$formulaUrl = str_replace('/propto', '\propto', $formulaUrl);
	$formulaUrl = str_replace('/sim', '\sim', $formulaUrl);
	$formulaUrl = str_replace('/simeq', '\simeq', $formulaUrl);
	$formulaUrl = str_replace('/smile', '\smile', $formulaUrl);
	$formulaUrl = str_replace('/sqsubset', '\sqsubset', $formulaUrl);
	$formulaUrl = str_replace('/sqsubseteq', '\sqsubseteq', $formulaUrl);
	$formulaUrl = str_replace('/sqsupset', '\sqsupset', $formulaUrl);
	$formulaUrl = str_replace('/sqsupseteq', '\sqsupseteq', $formulaUrl);
	$formulaUrl = str_replace('/subset', '\subset', $formulaUrl);
	$formulaUrl = str_replace('/subseteq', '\subseteq', $formulaUrl);
	$formulaUrl = str_replace('/succ', '\succ', $formulaUrl);
	$formulaUrl = str_replace('/succeq', '\succeq', $formulaUrl);
	$formulaUrl = str_replace('/supset', '\supset', $formulaUrl);
	$formulaUrl = str_replace('/supseteq', '\supseteq', $formulaUrl);
	$formulaUrl = str_replace('/vdash', '\vdash', $formulaUrl);
	$formulaUrl = str_replace('/|', '\|', $formulaUrl);

	// Greek lowercase
	$formulaUrl = str_replace('/alpha', '\alpha', $formulaUrl);
	$formulaUrl = str_replace('/beta', '\beta', $formulaUrl);
	$formulaUrl = str_replace('/chi', '\chi', $formulaUrl);
	$formulaUrl = str_replace('/delta', '\delta', $formulaUrl);
	$formulaUrl = str_replace('/epsilon', '\epsilon', $formulaUrl);
	$formulaUrl = str_replace('/eta', '\eta', $formulaUrl);
	$formulaUrl = str_replace('/gamma', '\gamma', $formulaUrl);
	$formulaUrl = str_replace('/iota', '\iota', $formulaUrl);
	$formulaUrl = str_replace('/kappa', '\kappa', $formulaUrl);
	$formulaUrl = str_replace('/lambda', '\lambda', $formulaUrl);
	$formulaUrl = str_replace('/mu', '\mu', $formulaUrl);
	$formulaUrl = str_replace('/nu', '\nu', $formulaUrl);
	$formulaUrl = str_replace('/omega', '\omega', $formulaUrl);
	$formulaUrl = str_replace('/phi', '\phi', $formulaUrl);
	$formulaUrl = str_replace('/pi', '\pi', $formulaUrl);
	$formulaUrl = str_replace('/psi', '\psi', $formulaUrl);
	$formulaUrl = str_replace('/rho', '\rho', $formulaUrl);
	$formulaUrl = str_replace('/sigma', '\sigma', $formulaUrl);
	$formulaUrl = str_replace('/tau', '\tau', $formulaUrl);
	$formulaUrl = str_replace('/theta', '\theta', $formulaUrl);
	$formulaUrl = str_replace('/upsilon', '\upsilon', $formulaUrl);
	$formulaUrl = str_replace('/varepsilon', '\varepsilon', $formulaUrl);
	$formulaUrl = str_replace('/varphi', '\varphi', $formulaUrl);
	$formulaUrl = str_replace('/varpi', '\varpi', $formulaUrl);
	$formulaUrl = str_replace('/varrho', '\varrho', $formulaUrl);
	$formulaUrl = str_replace('/varsigma', '\varsigma', $formulaUrl);
	$formulaUrl = str_replace('/vartheta', '\vartheta', $formulaUrl);
	$formulaUrl = str_replace('/xi', '\xi', $formulaUrl);
	$formulaUrl = str_replace('/zeta', '\zeta', $formulaUrl);

	// Greek capitals
	$formulaUrl = str_replace('/Delta', '\Delta', $formulaUrl);
	$formulaUrl = str_replace('/Gamma', '\Gamma', $formulaUrl);
	$formulaUrl = str_replace('/Lambda', '\Lambda', $formulaUrl);
	$formulaUrl = str_replace('/Omega', '\Omega', $formulaUrl);
	$formulaUrl = str_replace('/Phi', '\Phi', $formulaUrl);
	$formulaUrl = str_replace('/Pi', '\Pi', $formulaUrl);
	$formulaUrl = str_replace('/Psi', '\Psi', $formulaUrl);
	$formulaUrl = str_replace('/Sigma', '\Sigma', $formulaUrl);
	$formulaUrl = str_replace('/Theta', '\Theta', $formulaUrl);
	$formulaUrl = str_replace('/Upsilon', '\Upsilon', $formulaUrl);
	$formulaUrl = str_replace('/Xi', '\Xi', $formulaUrl);

	// Arrows
	$formulaUrl = str_replace('/Downarrow', '\Downarrow', $formulaUrl);
	$formulaUrl = str_replace('/Leftarrow', '\Leftarrow', $formulaUrl);
	$formulaUrl = str_replace('/Leftrightarrow', '\Leftrightarrow', $formulaUrl);
	$formulaUrl = str_replace('/Longleftarrow', '\Longleftarrow', $formulaUrl);
	$formulaUrl = str_replace('/Longleftrightarrow', '\Longleftrightarrow', $formulaUrl);
	$formulaUrl = str_replace('/Longrightarrow', '\Longrightarrow', $formulaUrl);
	$formulaUrl = str_replace('/Updownarrow', '\Updownarrow', $formulaUrl);
	$formulaUrl = str_replace('/Uparrow', '\Uparrow', $formulaUrl);
	$formulaUrl = str_replace('/downarrow', '\downarrow', $formulaUrl);
	$formulaUrl = str_replace('/gets', '\gets', $formulaUrl);
	$formulaUrl = str_replace('/hookleftarrow', '\hookleftarrow', $formulaUrl);
	$formulaUrl = str_replace('/hookrightarrow', '\hookrightarrow', $formulaUrl);
	$formulaUrl = str_replace('/leadsto', '\leadsto', $formulaUrl);
	$formulaUrl = str_replace('/leftarrow', '\leftarrow', $formulaUrl);
	$formulaUrl = str_replace('/leftharpoondown', '\leftharpoondown', $formulaUrl);
	$formulaUrl = str_replace('/leftharpoonup', '\leftharpoonup', $formulaUrl);
	$formulaUrl = str_replace('/leftrightarrow', '\leftrightarrow', $formulaUrl);
	$formulaUrl = str_replace('/longleftarrow', '\longleftarrow', $formulaUrl);
	$formulaUrl = str_replace('/longleftrightarrow', '\longleftrightarrow', $formulaUrl);
	$formulaUrl = str_replace('/longmapsto', '\longmapsto', $formulaUrl);
	$formulaUrl = str_replace('/longrightarrow', '\longrightarrow', $formulaUrl);
	$formulaUrl = str_replace('/mapsto', '\mapsto', $formulaUrl);
	$formulaUrl = str_replace('/nearrow', '\nearrow', $formulaUrl);
	$formulaUrl = str_replace('/nwarrow', '\nwarrow', $formulaUrl);
	$formulaUrl = str_replace('/rightarrow', '\rightarrow', $formulaUrl);
	$formulaUrl = str_replace('/rightharpoondown', '\rightharpoondown', $formulaUrl);
	$formulaUrl = str_replace('/rightharpoonup', '\rightharpoonup', $formulaUrl);
	$formulaUrl = str_replace('/rightleftharpoons', '\rightleftharpoons', $formulaUrl);
	$formulaUrl = str_replace('/searrow', '\searrow', $formulaUrl);
	$formulaUrl = str_replace('/swarrow', '\swarrow', $formulaUrl);
	$formulaUrl = str_replace('/to', '\to', $formulaUrl);
	$formulaUrl = str_replace('/updownarrow', '\updownarrow', $formulaUrl);
	$formulaUrl = str_replace('/uparrow', '\uparrow', $formulaUrl);

	// Accents
	$formulaUrl = str_replace('/acute', '\acute', $formulaUrl);
	$formulaUrl = str_replace('/bar', '\bar', $formulaUrl);
	$formulaUrl = str_replace('/breve', '\breve', $formulaUrl);
	$formulaUrl = str_replace('/check', '\check', $formulaUrl);
	$formulaUrl = str_replace('/ddot', '\ddot', $formulaUrl);
	$formulaUrl = str_replace('/dot', '\dot', $formulaUrl);
	$formulaUrl = str_replace('/grave', '\grave', $formulaUrl);
	$formulaUrl = str_replace('/hat', '\hat', $formulaUrl);
	$formulaUrl = str_replace('/mathring', '\mathring', $formulaUrl);
	$formulaUrl = str_replace('/tilde', '\tilde', $formulaUrl);
	$formulaUrl = str_replace('/vec', '\vec', $formulaUrl);

	// Others
	$formulaUrl = str_replace('/Im', '\Im', $formulaUrl);
	$formulaUrl = str_replace('/Join', '\Join', $formulaUrl);
	$formulaUrl = str_replace('/P', '\P', $formulaUrl);
	$formulaUrl = str_replace('/Re', '\Re', $formulaUrl);
	$formulaUrl = str_replace('/S', '\S', $formulaUrl);
	$formulaUrl = str_replace('/aleph', '\aleph', $formulaUrl);
	$formulaUrl = str_replace('/angle', '\angle', $formulaUrl);
	$formulaUrl = str_replace('/backslash', '\backslash', $formulaUrl);
	$formulaUrl = str_replace('/blacksquare', '\blacksquare', $formulaUrl);
	$formulaUrl = str_replace('/bot', '\bot', $formulaUrl);
	$formulaUrl = str_replace('/clubsuit', '\clubsuit', $formulaUrl);
	$formulaUrl = str_replace('/copyright', '\copyright', $formulaUrl);
	$formulaUrl = str_replace('/dashv', '\dashv', $formulaUrl);
	$formulaUrl = str_replace('/ell', '\ell', $formulaUrl);
	$formulaUrl = str_replace('/exists', '\exists', $formulaUrl);
	$formulaUrl = str_replace('/flat', '\flat', $formulaUrl);
	$formulaUrl = str_replace('/forall', '\forall', $formulaUrl);
	$formulaUrl = str_replace('/hbar', '\hbar', $formulaUrl);
	$formulaUrl = str_replace('/hearthsuit', '\hearthsuit', $formulaUrl);
	$formulaUrl = str_replace('/imath', '\imath', $formulaUrl);
	$formulaUrl = str_replace('/jmath', '\jmath', $formulaUrl);
	$formulaUrl = str_replace('/mho', '\mho', $formulaUrl);
	$formulaUrl = str_replace('/nabla', '\nabla', $formulaUrl);
	$formulaUrl = str_replace('/natural', '\natural', $formulaUrl);
	$formulaUrl = str_replace('/neg', '\neg', $formulaUrl);
	$formulaUrl = str_replace('/partial', '\partial', $formulaUrl);
	$formulaUrl = str_replace('/prime', '\prime', $formulaUrl);
	$formulaUrl = str_replace('/punds', '\punds', $formulaUrl);
	$formulaUrl = str_replace('/sharp', '\sharp', $formulaUrl);
	$formulaUrl = str_replace('/spadesuit', '\spadesuit', $formulaUrl);
	$formulaUrl = str_replace('/surd', '\surd', $formulaUrl);
	$formulaUrl = str_replace('/triangle', '\triangle', $formulaUrl);
	$formulaUrl = str_replace('/wp', '\wp', $formulaUrl);
	$formulaUrl = str_replace('/overline', '\overline', $formulaUrl);

	// European language
	$formulaUrl = str_replace('å', '\dot{a}', $formulaUrl);
	$formulaUrl = str_replace('/aa', '\dot{a}', $formulaUrl);



	// Find path
	$formulaSum = md5("$formulaUrl");
	$latexImg   = $storeDir . "/" . $formulaSum . ".png";

	if(!(file_exists("$latexImg"))){
		if(!(is_dir($storeDir))){
			$tempDir = "$documentRoot" . "_latex";
			if(!(is_dir($tempDir))){
				@mkdir("$tempDir");	
			}
			@mkdir("$storeDir");	
			if(!(is_dir($storeDir))){
				echo"<p>Error: $storeDir does not exists!</p>";die;
			}
		}

		$source = $SourceURL[0] . $formulaUrl;
		@copy("$source", "$latexImg");

		// Check if png is valid
		$imgsize = @getimagesize($latexImg);
		if($imgsize[0] == "" OR $imgsize[1] == ""){
			@unlink($latexImg);
			// echo"<p style=\"font:normal 8px verdana;\"><b style=\"color:red;\">Latex Error:</b> Copy from <a href=\"$source\">$source</a> to <a href=\"$latexImg\">$latexImg</a> failed.</p>";

			// We need to try next source
			$source = $SourceURL[1] . $formulaUrl;
			copy("$source", "$latexImg");
		}
	}

	/*- Find image ---------------------------------------------------------------- */
	$path = "";
	for($x=0;$x<10;$x++){
		$latexSrc = $path . "_latex/imgs/$formulaSum.png";
		if(file_exists("$latexSrc")){
			break;
		}
		else{
			$path = $path . "../";
		}
	}
	if(!(file_exists("$latexSrc"))){
		echo"<p><b>Latex Error:</b><br />
		storeDir: $storeDir<br />
		formulaURL: $formulaUrl<br />
		SourceURL[0]: $SourceURL[0]<br />
		SourceURL[0]: $SourceURL[1]<br />
			
		$latexSrc</p>";
	}

	/*- Find alt text ------------------------------------------------------------- */
	$imgAlt = "$formula";

	/*- Display latex ------------------------------------------------------------- */
	echo"<img src=\"$latexSrc\" alt=\"$imgAlt\" title=\"$imgAlt\" />";

}
?>