<page_footer>
<TABLE cellpadding=0 cellspacing=0 class="t2">
<TR>
	<td class="w20p h19"></td>
	<td class="w35p h19"></td>
	<TD class="w30p h19 border-l border-t border-r pad-l-5">
		<P class="p4 ft7">Total H.T.</P>
	</TD>
	<TD class="w15p h19 border-r border-t pad-r-5">
		<P class="p15 ft1"><?php echo number_format($facture['total_ht'], 2, '.', ' ');?></P>
	</TD>
</TR>
<TR>
	<td class="h19"></td>
	<td class="h19"></td>
	<TD class="h19 border-l border-r pad-l-5">
		<P class="p4 ft7"> Total T.V.A.</P>
	</TD>
	<TD class="h19 border-r pad-r-5">
		<P class="p15 ft1"><?php echo number_format($facture['total_tva'], 2, '.', ' ');?></P>
	</TD>
</TR>
<TR>
	<td class="h19"></td>
	<td class="h19"></td>
	<TD class="h19 border-l border-r pad-l-5">
		<P class="p4 ft7">Total T.T.C.</P>
	</TD>
	<TD class="h19 border-t border-r pad-r-5">
		<P class="p15 ft15"><?php echo number_format($facture['total_ttc'], 2, '.', ' ');?></P>
	</TD>
</TR>
<?php if (round($facture['acompte'],2) != 0.00) { ?>
<TR>
	<td class="h19"></td>
	<td class="h19"></td>
	<TD class="h19 border-l border-r pad-l-5">
		<P class="p4 ft7"> Acompte</P>
	</TD>
	<TD class="h19 border-r pad-r-5">
		<P class="p15 ft1"><?php echo number_format($facture['acompte'], 2, '.', ' ');?></P>
	</TD>
</TR>
<?php } ?>
<TR>
	<td class="h19"></td>
	<td class="h19"></td>
	<TD class="h19 border-l border-r pad-l-5">
		<P class="p4 ft7">Net à payer (Euros)</P></TD>
	<TD class="h19 border-r  border-t pad-r-5">
		<P class="p15 ft15"><?php echo number_format($facture['total_net'], 2, '.', ' ');?></P>
	</TD>
</TR>
<TR>
	<td class="h19"></td>
	<TD class="h19 border-l border-t pad-l-5">
		<P class="p4 ft7">Règlement : </P>
	</TD>
	<td class="border-t border-r h19 pad-l-5">
		<p class="p4 ft1">Comptant + 20 J</p></td>
	<td class="border-t h19"></td>
</TR>
<TR>
	<td class="h19"></td>
	<TD class="h19 border-l border-b pad-l-5">
		<P class="p4 ft7">Echéance de 100,00 % au 19/02/17</P></TD>
	<TD class="h19 border-b border-r pad-l-5">
		<P class="p4 ft1"><?php echo number_format($facture['total_net'], 2, '.', ' ');?></P>
	</TD>
	<td class="h19"></td>
</TR>
</TABLE>
<P class="p4 ft19 txt-align-c margin-t-5"><?php echo $ste['nom'];?> - SIRET : <?php echo $ste['siret'];?> - APE : <?php echo $ste['ape'];?> - N° T.V.A : <?php echo $ste['tva_intracom'];?> - Tel <?php echo $ste['portable'];?> - Email <?php echo $ste['email'];?></P>
</page_footer>

