<TABLE cellpadding=0 cellspacing=0 class="t3 border-l border-r 
<?php 
	if ($closeTable) echo 'border-b'; if ($startTable) echo ' border-t';?>">
<TR>
	<TD <?php $padB = 1.33; 
			if ($ligne['type'] === 'tranche_debut' || 
			$ligne['type'] === 'tranche_fin') $padB = 4.3;
			if ($paddingB) $padB += $paddingU - 1.33;
			echo 'style="padding-bottom: '.$padB.'mm;"';?> 
	class="w10p border-r vert-align-t pad5m">
		<p class="p4 <?php 
		if ($ligne['type'] === 'standard') { echo 'ft1';}
		else if ($ligne['type'] === 'tranche_debut') {echo 'ft2';} ?>">
		<?php echo $ligne['num_ligne']; ?></p>
	</TD>

	<TD <?php $padB = 1.33; 
			if ($ligne['type'] === 'tranche_debut' || 
			$ligne['type'] === 'tranche_fin') $padB = 4.3;
			if ($paddingB) $padB += $paddingU - 1.33;
			echo 'style="padding-bottom: '.$padB.'mm;"';?> 
	class="w50p border-r vert-align-t pad5m">
		<p class="<?php 
		if ($ligne['type'] === 'standard') { echo 'p4 ft1';}
		else if ($ligne['type'] === 'tranche_debut') {echo 'p4 ft2';}
		else if ($ligne['type'] === 'tranche_fin') {echo 'p15 ft5';} ?>">
		<?php echo nl2br($ligne['libelle']); ?></p>
	</TD>

	<TD <?php $padB = 1.33; 
			if ($ligne['type'] === 'tranche_debut' || 
			$ligne['type'] === 'tranche_fin') $padB = 4.3;
			if ($paddingB) $padB += $paddingU - 1.33;
			echo 'style="padding-bottom: '.$padB.'mm;"';?> 
	class="w5p border-r vert-align-t txt-align-c pad5m <?php 
	if ($ligne['type'] === 'tranche_fin') echo 'border-t-double';?>">
		<p class="p15 <?php 
		if ($ligne['type'] === 'standard') { echo 'ft1';}
		else if ($ligne['type'] === 'tranche_debut') {echo 'ft2';} ?>">
		<?php echo $ligne['unite']; ?></p>
	</TD>

	<TD <?php $padB = 1.33; 
			if ($ligne['type'] === 'tranche_debut' || 
			$ligne['type'] === 'tranche_fin') $padB = 4.3;
			if ($paddingB) $padB += $paddingU - 1.33;
			echo 'style="padding-bottom: '.$padB.'mm;"';?> 
	class="w10p border-r vert-align-t pad5m <?php 
	if ($ligne['type'] === 'tranche_fin') echo 'border-t-double';?>">
		<p class="p15 <?php 
		if ($ligne['type'] === 'standard') { echo 'ft1';}
		else if ($ligne['type'] === 'tranche_debut') {echo 'ft2';} ?>">
		<?php echo number_format($ligne['ht'], 2, '.', ' '); ?></p>
	</TD>

	<TD <?php $padB = 1.33; 
			if ($ligne['type'] === 'tranche_debut' || 
			$ligne['type'] === 'tranche_fin') $padB = 4.3;
			if ($paddingB) $padB += $paddingU - 1.33;
			echo 'style="padding-bottom: '.$padB.'mm;"';?> 
	class="w10p border-r vert-align-t pad5m <?php 
	if ($ligne['type'] === 'tranche_fin') echo 'border-t-double';?>">
		<p class="p15 <?php 
		if ($ligne['type'] === 'standard') { echo 'ft1';}
		else if ($ligne['type'] === 'tranche_debut') {echo 'ft2';} ?>">
		<?php echo number_format($ligne['quantite'], 3, '.', ' '); ?></p>
	</TD>

	<TD <?php $padB = 1.33; 
			if ($ligne['type'] === 'tranche_debut' || 
			$ligne['type'] === 'tranche_fin') $padB = 4.3;
			if ($paddingB) $padB += $paddingU - 1.33;
			echo 'style="padding-bottom: '.$padB.'mm;"';?> 
	class="w15p vert-align-t pad5m <?php 
	if ($ligne['type'] === 'tranche_fin') echo 'border-t-double';?>">
		<p class="p15 <?php 
		if ($ligne['type'] === 'standard') { echo 'ft1';}
		else if ($ligne['type'] === 'tranche_debut') {echo 'ft2';} 
		else if ($ligne['type'] === 'tranche_fin') {echo 'ft5';}?>">
		<?php echo number_format($ligne['total_ht'], 2, '.', ' '); ?></p>
	</TD>
</TR>
</TABLE>
