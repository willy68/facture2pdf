<table>
	<tr>
		<td class="tdlogo">
			<img src="<?php echo $ste['logo'];?>" class="logo">
		</td>
		<td class="tdaddr pad-l pad-r">
			<P class="paddr ft4"><?php echo $ste['nom'];?></p>
			<p class="paddr ft6">
			<?php echo $ste['adresse'];?><br>
			<?php echo $ste['suite_adresse'];?><br>
			<?php echo $ste['cp'];?> <?php echo $ste['ville'];?><br>
			Email: <?php echo $ste['email'];?> - Tel: <?php echo $ste['portable'];?>
			</p>
		</td>
	</tr>
</table>

<table cellpadding=0 cellspacing=0 class="t0 border">
	<tr>
		<td colspan=2 class="h54p w45p border-r"><P class="paddr ft3">Facture</P></td>
		<td rowspan=5 class="w55p vert-align-c">

			<p style="margin: 0 0 0 10%;"> 
				<span class="p6 ft4"><?php echo $client['nom'];?></span><br>
				<span class="p6 ft6"><?php echo $adresse_client['adresse_1'];?></span><br>
				<span class="p6 ft6"><?php echo $adresse_client['adresse_2'];?></span><br>
				<span class="p6 ft6"><?php echo $adresse_client['adresse_3'];?></span><br>
				<span class="p6 ft6"><?php echo $adresse_client['cp'];?> <?php echo $adresse_client['ville'];?></span>
			</p>

		</td>
	</tr>
	<tr>
		<td colspan=2 class="h24 border-t border-b border-r">
			<p class="p7 ft1"><?php echo $ste['ville'];?>, le <?php echo $facture['date_edition'];?></p>
		</td>
	</tr>
	<tr>
		<td class="h19 w15p">
			<p class="p7 ft7"> Référence:</P>
		</td>
		<td class="w30p border-r">
			<p class="p4 ft1"><?php echo $facture['code_facture'];?></p>
		</td>
	</tr>
	<tr>
		<td class="h19">
			<p class="p7 ft7">Du:</p>
		</td>
		<td class="border-r">
			<p class="p4 ft1"><?php echo $facture['created_at'];?></p>
		</td>
	</tr>
	<tr>
		<td class="h19">
			<p class="p7 ft7">Code client:</p></td>
		<td class="border-r">
			<p class="p4 ft1"><?php echo $client['code_client'];?></p>
		</td>
	</tr>
</table>

<table cellpadding=0 cellspacing=0 class="t4 border-l border-r border-b">
<tr>
		<td class="w100p h19"><p class="p7 ft7">Objet:</P></td>
	</tr>
	<tr>
		<td class="w100p h48p p4 ft1"><?php echo $facture['objet'];?></td>
	</tr>
</table>