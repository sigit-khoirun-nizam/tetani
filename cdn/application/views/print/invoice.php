<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="<?=base_url()?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=base_url()?>/assets/css/all.min.css">
	<link rel="stylesheet" href="<?=base_url()?>/assets/css/util.css">
	<link rel="stylesheet" href="<?=base_url()?>/assets/css/minmin.css?v=<?=time()?>">
</head>
<body onload="window.print();setTimeout(function(){window.close();},3000);">
	<div class="nota">
		<?php
			$trxid = (isset($_GET["id"])) ? intval($_GET["id"]) : 0;
			if($trxid != 0){
				$trx = $this->func->getTransaksi($trxid,"semua");
				$byr = $this->func->getBayar($trx->idbayar,"semua");
				
				$alamat = $this->func->getAlamat($trx->alamat,"semua");
				$kec = $this->func->getKec($alamat->idkec,"semua");
				$kab = $this->func->getKab($kec->idkab,"semua");
				$prov = $this->func->getProv($kab->idprov,"nama");
				$lkp = $kec->nama." ".$kab->nama." ".$prov." ".$alamat->kodepos;
		?>
			<div class="header row m-lr-0 m-t-10 m-b-20">
				<div class="col-8"><b style="font-size:120%;"><i class="fas fa-file-invoice"></i> Nota Penjualan #<?=$trx->orderid?></b></div>
				<div class="col-4"><?=$this->func->ubahTgl("D, d M Y",$trx->tgl)?></div>
			</div>
			<div class="row m-lr-0">
				<div class="col-4">
					<?php if($trx->dropship == ""){ ?>
					<div class="content">
						<b><u>Pengirim</u></b><br/>
						<b style="font-size:120%;"><?=$this->func->globalset("nama")?></b> 
						(Telp. <b><?=$this->func->globalset("notelp")?></b>)<br/>
						<?=$this->func->getKab($this->func->globalset("kota"),"nama")?>
					</div>
					<?php }else{ ?>
					<div class="content">
						<b><u>Pengirim</u></b><br/>
						<b style="font-size:120%;"><?=$trx->dropship?></b> 
						(Telp. <b><?=$trx->dropshipnomer?></b>)<br/>
						<?=$trx->dropshipalamat?>
					</div>
					<?php } ?>
				</div>
				<div class="col-4">
					<b><u>Penerima</u></b><br/>
					<b style="font-size:120%;"><?=$alamat->nama?></b> 
					(Telp. <b><?=$alamat->nohp?></b>)<br/>
					<?=$alamat->alamat."<br/>".$lkp?>
				</div>
				<div class="col-4">
					<table>
						<tr><th>Agen</td><td><?=$this->func->getUserdata($trx->usrid,"nama")?></th></tr>
						<tr><th>Invoice</th><th>#<?=$trx->orderid?></th></tr>
					</table>
				</div>
			</div>
			<div class="m-t-20">
				<table class="table table-sm table-bordered">
					<tr>
						<th>Kode</th>
						<th>Nama Produk</th>
						<th>QTY</th>
						<th>Berat</th>
						<th>Harga</th>
						<th>Jumlah</th>
						<th>Total</th>
					</tr>
					<?php
						//$this->db->select("SUM(jumlah) as jml,idproduk,harga,jumlah,diskon
						$this->db->where("idtransaksi",$trx->id);
						$db = $this->db->get("transaksiproduk");
						$total = 0;
						$totalqty = 0;
						$totalberat = 0;
						$ket = "";
						foreach($db->result() as $r){
							$prod = $this->func->getProduk($r->idproduk,"semua");
							$total += ($r->diskon+$r->harga)*$r->jumlah;
							$totalberat += !empty($prod) ? $prod->berat : 0;
							$berat = !empty($prod) ? $prod->berat : 0;
							$kode = !empty($prod) ? $prod->kode : 0;
							$nama = !empty($prod) ? $prod->nama : "Produk dihapus";
							$totalqty += $r->jumlah;
							$ket .= $r->keterangan."<br/>";
							echo "
								<tr>
									<td>".$kode."</td>
									<td>".$nama."</td>
									<td class=\"text-center\">".$r->jumlah."</td>
									<td class=\"text-right\">".$berat*$r->jumlah."</td>
									<td class=\"text-right\">".$this->func->formUang($r->harga)."</td>
									<td class=\"text-right\">".$this->func->formUang($r->diskon+$r->harga)."</td>
									<td class=\"text-right\">".$this->func->formUang(($r->diskon+$r->harga)*$r->jumlah)."</td>
								</tr>
							";
						}
					?>
					<tr>
						<th colspan=2></th>
						<th class="text-center"><?=$totalqty?></th>
						<th class="text-right"><?=$totalberat?></th>
						<th colspan=2></th>
						<th class="text-right"><?=$this->func->formUang($total)?></th>
					</tr>
					<tr>
						<td colspan=4 rowspan=3>
							KETERANGAN:<br/>
							<small><?=$ket?></small>
						</td>
						<th colspan=2 class="text-right">Ongkir <?=strtoupper(strtolower($trx->kurir." ".$trx->paket))?></th>
						<th class="text-right"><?=$this->func->formUang($trx->ongkir)?></th>
					</tr>
					<tr>
						<th colspan=2 class="text-right">Diskon</th>
						<th class="text-right">-<?=$this->func->formUang($byr->diskon)?></th>
					</tr>
					<tr>
						<th colspan=2 class="text-right">Grand Total</th>
						<th class="text-right"><?=$this->func->formUang($total+$trx->ongkir-$byr->diskon)?></th>
					</tr>
				</table>
			</div>
		<?php
			}
		?>
	</div>
</body>
</html>