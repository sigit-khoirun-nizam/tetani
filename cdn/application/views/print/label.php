<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="<?=base_url()?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=base_url()?>/assets/css/minmin.css?v=<?=time()?>">
</head>
<body onload="window.print();setTimeout(function(){window.close();},3000);">
	<div class="labelkirim">
		<?php
			$trxid = (isset($_GET["id"])) ? intval($_GET["id"]) : 0;
			if($trxid != 0){
				$trx = $this->func->getTransaksi($trxid,"semua");
				$alamat = $this->func->getAlamat($trx->alamat,"semua");
				$kec = $this->func->getKec($alamat->idkec,"semua");
				$kab = $this->func->getKab($kec->idkab,"semua");
				$prov = $this->func->getProv($kab->idprov,"nama");
				$lkp = $kec->nama." ".$kab->nama." ".$prov." ".$alamat->kodepos;
		?>
			<div class="header">
				<?php if($trx->dropship == ""){ ?>
					<img src="<?=base_url("assets/img/".$this->func->globalset("logo"))?>" class="logo" />
				<?php } ?>
			</div>
			<div class="row">
				<?php if($trx->dropship == ""){ ?>
				<div class="content col-6 p-lr-0">
					<b><u>Pengirim</u></b><br/>
					<b style="font-size:120%;"><?=$this->func->globalset("nama")?></b> 
					(Telp. <b><?=$this->func->globalset("notelp")?></b>)<br/>
					<?=$this->func->getKab($this->func->globalset("kota"),"nama")?>
				</div>
				<?php }else{ ?>
				<div class="content col-6 p-lr-0">
					<b><u>Pengirim</u></b><br/>
					<b style="font-size:120%;"><?=$trx->dropship?></b> 
					(Telp. <b><?=$trx->dropshipnomer?></b>)<br/>
					<?=$trx->dropshipalamat?>
				</div>
				<?php } ?>
				<div class="content col-6 p-lr-0">
					<b><u>Penerima</u></b><br/>
					<b style="font-size:120%;"><?=$alamat->nama?></b> 
					(Telp. <b><?=$alamat->nohp?></b>)<br/>
					<?=$alamat->alamat."<br/>".$lkp?>
				</div>
			</div>
		<?php
			}
		?>
	</div>
</body>
</html>