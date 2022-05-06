<?php
/**
*
* File: _admin/_inc/muscles/_liquibase/muscle_groups_translations.php
* Version 1.0.0
* Date 12:57 24.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_muscle_groups_translations") or die(mysqli_error($link)); 


echo"

	<!-- muscle_groups_translations -->
	";
	$query = "SELECT * FROM $t_muscle_groups_translations";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_muscle_groups_translations: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_muscle_groups_translations(
	  	 muscle_group_translation_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(muscle_group_translation_id), 
	  	   muscle_group_translation_muscle_group_id INT,
	  	   muscle_group_translation_language VARCHAR(25),
	  	   muscle_group_translation_name VARCHAR(250),
	  	   muscle_group_translation_text TEXT)")
		   or die(mysqli_error());

		$nettport_muscle_groups_translations = array(
  array('muscle_group_translation_id' => '1','muscle_group_translation_muscle_group_id' => '1','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Arms','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '2','muscle_group_translation_muscle_group_id' => '1','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Armer','muscle_group_translation_text' => '<h2>Muskler i armen</h2>
<p>Coracobrachialis er den minste av de tre muskler som festes til skulderbladene. (De to andre musklene er pectoralis minor og biceps brachii.) Coracobrachialis ligger i den &oslash;vre og mediale del av armen.</p>
<p>Biceps brachii er et tohodet muskel som ligger p&aring; overarmen mellom skulderen og albuen. Musklen starter p&aring; skulderbladet og magen, og ender p&aring; &oslash;vre underarm. Mens biceps krysser b&aring;de skulder og albueledd, er dens viktigste funksjon p&aring; sistnevnte. Begge disse bevegelsene blir brukt n&aring;r du &aring;pner en flaske med en korketrekker. F&oslash;rste biceps skrur korken (supinasjon), s&aring; det trekker ut korken (fleksjon).</p>
<p>Brachialis (brachialis anticus) er en muskel i overarmen som b&oslash;yer albueleddet. Den ligger dypere enn biceps brachii.</p>
<p>Triceps brachii muskelen er den store muskelen p&aring; baksiden av den &oslash;vre arm. Muskelen skal hovedsakelig forlenge av albueleddet (utretting av armen).</p>
<p>Anconeus muskel er en liten muskel p&aring; bakre del av albueleddet. Noen anser anconeus &aring; v&aelig;re en videref&oslash;ring av triceps brachii.</p>
<p>Articularis cubiti muskel er en muskel av albuen. Den er oftes en del av triceps brachii.</p>'),
  array('muscle_group_translation_id' => '3','muscle_group_translation_muscle_group_id' => '5','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Rygg','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '4','muscle_group_translation_muscle_group_id' => '5','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Back','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '5','muscle_group_translation_muscle_group_id' => '10','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Chest','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '6','muscle_group_translation_muscle_group_id' => '10','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Bryst','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '7','muscle_group_translation_muscle_group_id' => '13','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Rompe, l&aring;r og legger','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '8','muscle_group_translation_muscle_group_id' => '13','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Hip and legs','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '9','muscle_group_translation_muscle_group_id' => '20','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Sholder','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '10','muscle_group_translation_muscle_group_id' => '20','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Skulder','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '11','muscle_group_translation_muscle_group_id' => '22','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Mage','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '12','muscle_group_translation_muscle_group_id' => '22','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Stomach','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '13','muscle_group_translation_muscle_group_id' => '2','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Anterior','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '14','muscle_group_translation_muscle_group_id' => '2','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Anterior','muscle_group_translation_text' => '<p>Anterior betyr den fremre delen av armen.</p>
<p><span id="result_box" class="" lang="no"><span class="">Den fremre delen inneholder tre muskler:</span> </span></p>
<ul>
<li><span id="result_box" class="" lang="no">biceps brachii </span></li>
<li><span id="result_box" class="" lang="no">brachialis<br /></span></li>
<li><span id="result_box" class="" lang="no"> coracobrachialis</span><span id="result_box" class="" lang="no"></span></li>
</ul>
<p><span id="result_box" class="" lang="no">Disse musklene er alle innervated av muskulokutane nerven som oppst&aring;r fra femte, sjette og syvende cervical spinal nerver. Blodforsyningen er fra brachialarterien.</span></p>'),
  array('muscle_group_translation_id' => '15','muscle_group_translation_muscle_group_id' => '3','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Posterior','muscle_group_translation_text' => '<p><span id="result_box" class="" lang="no"><span class="">Den bakre delen av armen har som hovedfunskjon &aring; forlenge armens bevegelse.</span><br /><br />Musklene vi finner i armer posterior er: </span></p>
<ul>
<li><span id="result_box" class="" lang="no">triceps brachii</span></li>
<li><span id="result_box" class="" lang="no">anconeus muskler</span></li>
</ul>
<p><span id="result_box" class="" lang="no">Neveforsnynging er fra radialnerven. Blodforsyning er fra profunda brachii.<br /><br />Triceps brachii er en stor muskel som inneholder tre hoder en lateral, medial og midt. Anconeus er en liten muskel som stabiliserer albueforbindelsen under bevegelse. <span class="">Noen embryologer anser det som det fjerde hodet til triceps brachia, da &oslash;vre og nedre lemmer har tilsvarende embryologisk opprinnelse, og underbenet inneholder quadriceps femoris-muskelen som har fire hoder, og er den nedre delen av triceps-ekvivalenten.</span></span></p>'),
  array('muscle_group_translation_id' => '16','muscle_group_translation_muscle_group_id' => '4','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Other','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '17','muscle_group_translation_muscle_group_id' => '3','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Posterior','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '18','muscle_group_translation_muscle_group_id' => '6','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Splenius','muscle_group_translation_text' => '<p><span id="result_box" class="" lang="no"><span class="">Splenius best&aring;r av to muskler:</span><br /></span></p>
<ul>
<li><span id="result_box" class="" lang="no">Splenius capitus muskel</span></li>
<li><span id="result_box" class="" lang="no">Splenius cervicis muskel<br /></span><span id="result_box" class="" lang="no"></span></li>
</ul>
<p><span id="result_box" class="" lang="no">Musklene starter i &oslash;vre thorax og lavere cervical spinous. Funksjonene til musklene er &aring; forlenge og gi rotasjon til hodet og nakken.</span></p>'),
  array('muscle_group_translation_id' => '19','muscle_group_translation_muscle_group_id' => '7','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Erector spinae','muscle_group_translation_text' => '<p><span id="result_box" class="" lang="no"><span class="">Erektor spinae er et sett med muskler som retter og roterer ryggen.</span></span></p>
<h2><span class="" lang="no"><span class="">Struktur</span></span></h2>
<p><span class="" lang="no"><span class=""><span id="result_box" class="" lang="no"><span title="The erector spinae is not just one muscle, but a bundle of muscles and tendons.">Erektor spinae er ikke bare en muskel, men et bunt av muskler og sener. </span><span title="It is paired and runs more or less vertically.">Den er parret og g&aring;r mer eller mindre vertikalt. </span><span title="It extends throughout the lumbar, thoracic and cervical regions, and lies in the groove to the side of the vertebral column.">Den strekker seg gjennom lumbal, thorax og cervical omr&aring;der, og ligger i sporet til siden av vertebral kolonnen. </span><span title="The erector spinae is covered in the lumbar and thoracic regions by the thoracolumbar fascia, and in the cervical region by the nuchal ligament.

">Den erektor spinae er dekket i lumbal og thoracic regioner ved thoracolumbar fascia, og i den cervical regionen av nuchal ligament.<br /><br /></span><span title="This large muscular and tendinous mass varies in size and structure at different parts of the vertebral column.">Denne store muskel- og tendin&oslash;se massen varierer i st&oslash;rrelse og struktur p&aring; forskjellige deler av vertebral kolonnen. </span><span title="In the sacral region, it is narrow and pointed, and at its origin chiefly tendinous in structure.">I sakralomr&aring;det er den smal og spiss, og har sin opprinnelse hovedsakelig tendent i struktur. </span><span title="In the lumbar region, it is larger, and forms a thick fleshy mass.">I lumbaleomr&aring;det er den st&oslash;rre, og danner en tykk kj&oslash;ttfull masse. </span><span title="Further up, it is subdivided into three columns.">Videre er det oppdelt i tre kolonner. </span><span title="They gradually diminish in size as they ascend to be inserted into the vertebrae and ribs.

">De reduseres gradvis i st&oslash;rrelse n&aring;r de stiger opp for &aring; bli satt inn i vertebrae og ribber.<br /><br /></span><span title="The erector spinae arises from the anterior surface of a broad and thick tendon.">Den erektor spinae oppst&aring;r fra den fremre overflaten av en bred og tykk sene. </span><span title="It is attached to the medial crest of the sacrum, to the spinous processes of the lumbar and the eleventh and twelfth thoracic vertebrae and the supraspinous ligament, to the back part of the inner lip of the iliac crests, and to the lateral crests of the">Den er festet til sokkelens mediale kors, til spindelprosessene i lumbale og den ellevte og tolvte thoraxvirtebrae og det supraspin&oslash;se ligamentet, til den bakre delen av den indre lip av iliackrestene, og til sidekamrene i </span><span title="sacrum, where it blends with the sacrotuberous and posterior sacroiliac ligaments.

">sacrum, hvor det blandes med sacrotuberous og posterior sacroiliac ligaments.<br /><br /></span><span title="Some of its fibers are continuous with the fibers of origin of the gluteus maximus.

">Noen av dens fibre er kontinuerlige med opprinnelsesfibrene til gluteus maximus.<br /><br /></span><span title="The muscular fibers form a large fleshy mass that splits, in the upper lumbar region, into three columns, viz., a lateral (Iliocostalis), an intermediate (Longissimus), and a medial (Spinalis).">De muskelfibre danner en stor kj&oslash;ttmasse som i &oslash;vre lumbalomr&aring;det deler seg i tre kolonner, nemlig en lateral (Iliocostalis), et mellomprodukt (Longissimus) og en medial (Spinalis). </span><span title="Each of these consists of three parts, inferior to superior, as follows:">Hver av disse best&aring;r av tre deler, d&aring;rligere enn overlegen, som f&oslash;lger:</span></span></span></span></p>
<ul>
<li><span class="" lang="no"><span class=""><span class="" lang="no"><span title="Each of these consists of three parts, inferior to superior, as follows:"> Iliocostalis</span></span></span></span></li>
<li><span class="" lang="no"><span class=""><span class="" lang="no"><span title="Each of these consists of three parts, inferior to superior, as follows:">Longissimus</span></span></span></span></li>
<li><span class="" lang="no"><span class=""><span class="" lang="no"><span title="Each of these consists of three parts, inferior to superior, as follows:">Spinalis</span></span></span></span></li>
</ul>
<p>&nbsp;</p>'),
  array('muscle_group_translation_id' => '20','muscle_group_translation_muscle_group_id' => '8','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Transversospinales','muscle_group_translation_text' => '<p>Transversospinalene er en gruppe muskler i ryggen. Musklene brukes til rotasjon og forlengelse av vertebral. Disse musklene er sm&aring; og har en d&aring;rlig mekanisk fordel for &aring; bidra til bevegelse. </p>
<p>Musklene i muskelgruppen:</p>
<ul>
<li>de tre semispinalis musklene, spenner over 4-6 vertebrale segmenter
<ul>
<li>semispinalis thoracis</li>
<li>semispinalis cervicis</li>
<li>semispinalis capitis</li>
</ul>
</li>
<li>multifidus, som strekker seg over 2-4 vertebrale segmenter</li>
<li>rotatorer, spenner over 1-2 vertebrale segmenter
<ul>
<li>rotatorene cervicis</li>
<li>rotatorene thoracis</li>
<li>rotatorene lumborum</li>
</ul>
</li>
<li>interspinales</li>
<li>intertransversarii</li>
</ul>'),
  array('muscle_group_translation_id' => '21','muscle_group_translation_muscle_group_id' => '9','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Vertebral column','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '22','muscle_group_translation_muscle_group_id' => '11','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Muscles','muscle_group_translation_text' => '<p><span id="result_box" class="" lang="no" tabindex="-1"><span class="">Serratus bakre underverdig trekker de nedre ribber bakover og nedover for &aring; hjelpe til med rotasjon og forlengelse av stammen.</span> <span class="">Denne bevegelsen av ribben bidrar ogs&aring; til tvungen utl&oslash;p av luft fra lungene.</span></span></p>'),
  array('muscle_group_translation_id' => '23','muscle_group_translation_muscle_group_id' => '12','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Thoracic cavity','muscle_group_translation_text' => '<p><span id="result_box" class="" lang="no" tabindex="-1"><span class="">Brysthulen er kammeret til vertebrater som er beskyttet av thoracic veggen (ribbe bur og tilh&oslash;rende hud, muskel og fascia).</span> Det sentrale rommet i thoracic cavity er mediastinum. <span class="">Det er to &aring;pninger i thoracic hule, en overlegent thorax&aring;pning kjent som thoraxinnl&oslash;pet og en lavere inferior thorax&aring;pning kjent som thoraxutl&oslash;pet.</span><br /><br /><span class="">Brysthulen inneholder sener og kardiovaskul&aelig;r system som kan bli skadet fra skade p&aring; rygg eller nakke.</span></span></p>'),
  array('muscle_group_translation_id' => '24','muscle_group_translation_muscle_group_id' => '14','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Buttocks','muscle_group_translation_text' => '<p><span id="result_box" class="" lang="no" tabindex="-1"><span class="">Rompen dannet av massene av gluteal muskler eller "glutes" (gluteus maximus muskel og gluteus medius muskel) overlagret av et lag av fett.</span> <span class="">Det overlegne aspektet av rumpen ender p&aring; iliackampen, og det nedre aspektet er skissert av den horisontale gluteale kr&oslash;llen.</span> <span class="">Gluteus maximus har to innsettingspunkter: 1/3 overordnet del av linja aspera i l&aring;rbenet, og den overlegne delen av iliotibialtraktoren.</span> <span class="">Massene av gluteus maximus muskelen skilles fra et mellomliggende intergluteal kl&oslash;ft</span></span></p>'),
  array('muscle_group_translation_id' => '25','muscle_group_translation_muscle_group_id' => '15','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Anterior','muscle_group_translation_text' => '<p><span id="result_box" class="" lang="no"><strong>Hva er gluteus hofte og bein anterior?</strong></span></p>
<p><span id="result_box" class="" lang="no">Den fremre delen av l&aring;ret inneholder muskler som strekker kneet og b&oslash;yer hoften.</span></p>
<p><span class="" lang="no"><strong>Hva brukes den til?</strong></span></p>
<p><span class="" lang="no"><span id="result_box" class="" lang="no"><span class="">Den fremre delen av l&aring;ret inneholder muskler som er extensorer av kneet og flexorene i hofteleddene.</span></span></span></p>
<p><span class="" lang="no"><strong>Hvor er den lokalisert?</strong></span></p>
<p><span class="" lang="no"><span id="result_box" class="" lang="no" tabindex="-1">Den fremre delen er en av de fascielle delene av l&aring;ret som inneholder grupper av muskler sammen med deres nerver og blodtilf&oslash;rsel. Den fremre delen inneholder sartorius-muskelen (den lengste muskelen i kroppen) og quadriceps femoris-gruppen, som best&aring;r av rectus femoris-muskelen og de tre enorme musklene - vastus lateralis, vastus intermedius og vastus medialis. <br /><br /><span class="">Den iliopsoas er noen ganger ansett som medlem av fremre delmuskulaturen, som er articularis-genusmuskel. </span><br /><br /><span class="">Det fremre kammeret er adskilt fra det bakre kammeret ved den laterale intermuskul&aelig;re septum og fra medialkammeret ved den mediale intermuskul&aelig;re septum.</span></span></span></p>'),
  array('muscle_group_translation_id' => '26','muscle_group_translation_muscle_group_id' => '16','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Posterior','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '27','muscle_group_translation_muscle_group_id' => '17','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Medial','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '28','muscle_group_translation_muscle_group_id' => '18','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Lateral','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '29','muscle_group_translation_muscle_group_id' => '19','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Foot','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '30','muscle_group_translation_muscle_group_id' => '21','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Muscles','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '31','muscle_group_translation_muscle_group_id' => '23','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Abdominal wall','muscle_group_translation_text' => '<p><span id="result_box" class="" lang="no" tabindex="-1"><span title="There are three layers of the abdominal wall.">Det er tre lag i bukveggen. </span><span title="They are, from the outside to the inside: external oblique, internal oblique, and transverse abdominal.[5]">De er, fra utsiden til innsiden: utvendig skr&aring;, innvendig skr&aring; og tverrg&aring;ende buk.&nbsp; </span><span title="The first three layers extend between the vertebral column, the lower ribs, the iliac crest and pubis of the hip.">De tre f&oslash;rste lagene strekker seg mellom vertebral kolonnen, de nedre ribber, iliackampen og hoften i hoften. </span><span title="All of their fibers merge towards the midline and surround the rectus abdominis in a sheath before joining up on the opposite side at the linea alba.">Alle deres fibre fletter seg mot midtlinjen og omgir rectus abdominis i en skjede f&oslash;r de g&aring;r sammen p&aring; motsatt side p&aring; linea alba. </span><span title="Strength is gained by the criss-crossing of fibers, such that the external oblique are downward and forward, the internal oblique upward and forward, and the transverse abdominal horizontally forward.[5]

">Styrken er oppn&aring;dd ved kryssoverf&oslash;ring av fibre, slik at den ytre skjevet er nedover og fremover, det indre skr&aring;t oppover og fremover, og den tverrg&aring;ende buen horisontalt fremover. <br /><br /></span><span title="The transverse abdominal muscle is flat and triangular, with its fibers running horizontally.">Tverrg&aring;ende muskelmasse er flatt og trekantet, med fibrene l&oslash;pende horisontalt. </span><span title="It lies between the internal oblique and the underlying transverse fascia.">Den ligger mellom det indre skr&aring; og den underliggende tverrfasaden. </span><span title="It originates from Poupart\'s ligament, the inner lip of the ilium, the lumbar fascia and the inner surface of the cartilages of the six lower ribs.">Den stammer fra Pouparts ligament, den indre lip av ilium, lumbale fascia og indre overflaten av bruskene av de seks nedre ribber. </span><span title="It inserts into the linea alba behind the rectus abdominis.

">Den legger inn i linea alba bak rectus abdominis.<br /><br /></span><span title="The rectus abdominis muscles are long and flat.">Muskler i rektal abdominis er lange og flate. </span><span title="The muscle is crossed by three fibrous bands called the tendinous intersections.">Muskelen krysses av tre fibr&oslash;se b&aring;nd som kalles de t&oslash;ffe kryssene. </span><span title="The rectus abdominis is enclosed in a thick sheath formed, as described above, by fibers from each of the three muscles of the lateral abdominal wall.">Rektus abdominis er innelukket i en tykk kappe formet, som beskrevet ovenfor, av fibre fra hver av de tre musklene i den laterale bukveggen. </span><span title="They originate at the pubis bone, run up the abdomen on either side of the linea alba, and insert into the cartilages of the fifth, sixth, and seventh ribs.">De stammer fra pubisbenet, l&oslash;per opp magen p&aring; hver side av linea alba, og setter inn i bruskene i femte, sjette og syvende ribben. </span><span title="In the region of the groin, the inguinal canal, a passage through the layers.">I regionen av lysken, inngangskanalen, et passasje gjennom lagene. </span><span title="This gap is where the testes can drop through the wall and where the fibrous cord from the uterus in the female runs.">Dette gapet er hvor testene kan falle gjennom veggen og hvor fiberledningen fra livmoren i hunnen l&oslash;per. </span><span title="This is also where weakness can form, and cause inguinal hernias.[5]

">Dette er ogs&aring; hvor svakhet kan danne, og for&aring;rsake inguinal brokk. <br /><br /></span><span title="The pyramidalis muscle is small and triangular.">Pyramidalis muskelen er liten og trekantet. </span><span title="It is located in the lower abdomen in front of the rectus abdominis.">Den ligger i underlivet foran rectus abdominis. </span><span title="It originates at the pubic bone and is inserted into the linea alba halfway up to the navel.">Den stammer fra kj&oslash;nnsbenet og settes inn i linea alba halvveis opp til navlen.</span></span></p>'),
  array('muscle_group_translation_id' => '32','muscle_group_translation_muscle_group_id' => '24','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Abdominal wall posterior','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '33','muscle_group_translation_muscle_group_id' => '25','muscle_group_translation_language' => 'no','muscle_group_translation_name' => 'Pelvis','muscle_group_translation_text' => NULL),
  array('muscle_group_translation_id' => '34','muscle_group_translation_muscle_group_id' => '4','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Other','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '35','muscle_group_translation_muscle_group_id' => '6','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Splenius','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '36','muscle_group_translation_muscle_group_id' => '7','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Erector spinae','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '37','muscle_group_translation_muscle_group_id' => '8','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Transversospinales','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '38','muscle_group_translation_muscle_group_id' => '9','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Vertebral column','muscle_group_translation_text' => '<p><span id="result_box" class="" lang="no" tabindex="-1"><span class="">Ryggraden er en del av det aksiale skjelettet.</span></span></p>'),
  array('muscle_group_translation_id' => '39','muscle_group_translation_muscle_group_id' => '11','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Muscles','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '40','muscle_group_translation_muscle_group_id' => '12','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Thoracic cavity','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '41','muscle_group_translation_muscle_group_id' => '14','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Buttocks','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '42','muscle_group_translation_muscle_group_id' => '15','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Anterior','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '43','muscle_group_translation_muscle_group_id' => '16','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Posterior','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '44','muscle_group_translation_muscle_group_id' => '17','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Medial','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '45','muscle_group_translation_muscle_group_id' => '18','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Lateral','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '46','muscle_group_translation_muscle_group_id' => '19','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Foot','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '47','muscle_group_translation_muscle_group_id' => '21','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Muscles','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '48','muscle_group_translation_muscle_group_id' => '23','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Abdominal wall anterior/lateral','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '49','muscle_group_translation_muscle_group_id' => '24','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Abdominal wall posterior','muscle_group_translation_text' => ''),
  array('muscle_group_translation_id' => '50','muscle_group_translation_muscle_group_id' => '25','muscle_group_translation_language' => 'en','muscle_group_translation_name' => 'Pelvis','muscle_group_translation_text' => '')
);

		

		foreach($nettport_muscle_groups_translations as $v){
			
			$muscle_group_translation_muscle_group_id = $v["muscle_group_translation_muscle_group_id"];
			$muscle_group_translation_language = $v["muscle_group_translation_language"];
			$muscle_group_translation_name = $v["muscle_group_translation_name"];
			$muscle_group_translation_text = $v["muscle_group_translation_text"];
		
			mysqli_query($link, "INSERT INTO $t_muscle_groups_translations
			(muscle_group_translation_id, muscle_group_translation_muscle_group_id, muscle_group_translation_language, muscle_group_translation_name) 
			VALUES 
			(NULL, '$muscle_group_translation_muscle_group_id', '$muscle_group_translation_language', '$muscle_group_translation_name')
			")
			or die(mysqli_error($link));
		}

	}
	echo"
	<!-- //muscle_groups_translations -->


";
?>