<?php
    if($_POST["usrid"] > 0){
    $user = $this->func->getUserdata($_POST["usrid"],"semua");
    $profil = $this->func->getProfil($_POST["usrid"],"semua","usrid");
?>
<div class="text-center m-t-20 m-b-30">
	<h4><b>LAPORAN TRANSAKSI PENGGUNA</b></h4><br/>
    Periode: <?=$this->func->ubahTgl("d/m/Y",$_POST["tglmulai"])?> sampai <?=$this->func->ubahTgl("d/m/Y",$_POST["tglselesai"])?><br/>&nbsp;<br/>
</div>
    <table class="col-md-6 table m-lr-auto">
        <tr>
            <th>Nama</th>
            <td>: <?=$profil->nama?></td>
        </tr>
        <tr>
            <th>No Telepon</th>
            <td>: <?=$profil->nohp?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td>: <?=$user->username?></td>
        </tr>
    </table>
<div class="table-responsive">
	<table class="table table-condensed table-hover table-bordered">
		<tr>
			<th scope="col">No</th>
			<th scope="col">Tanggal</th>
			<th scope="col">ID Transaksi</th>
			<th scope="col">Penerima</th>
			<th scope="col">No HP</th>
			<th scope="col">Diskon</th>
			<th scope="col">Ongkir</th>
			<th scope="col">Total</th>
		</tr>
	<?php
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$this->db->order_by("selesai","ASC");
		$this->db->where("status = '3' AND selesai BETWEEN '".$_POST["tglmulai"]." 00:00:00' AND '".$_POST["tglselesai"]." 23:59:59' AND usrid = '".$_POST["usrid"]."'");
		$db = $this->db->get("transaksi");
			
		if($db->num_rows() > 0){
			$no = 1;
			$total = 0;
			$totalongkir = 0;
			$totaldiskon = 0;
			foreach($db->result() as $r){
				$bayar = $this->func->getBayar($r->idbayar,"semua");
				$alamat = $this->func->getAlamat($r->alamat,"semua");
				$total += $bayar->total+$bayar->diskon-$bayar->kodebayar;
				$totalongkir += $r->ongkir;
				$totaldiskon += $bayar->diskon;
	?>
			<tr>
				<td><?=$no?></td>
				<td><?=$this->func->ubahTgl("d/m/Y H:i",$r->selesai)?></td>
				<td><?=$r->orderid?></td>
				<td><?=strtoupper(strtolower($alamat->nama))?></td>
				<td><?=strtoupper(strtolower($alamat->nohp))?></td>
				<td class='text-right'><?=$this->func->formUang($bayar->diskon)?></td>
				<td class='text-right'><?=$this->func->formUang($r->ongkir)?></td>
				<td class='text-right'><?=$this->func->formUang($bayar->total+$bayar->diskon-$bayar->kodebayar)?></td>
			</tr>
	<?php	
				$no++;
			}
			echo "
			<tr>
				<th class='text-right' colspan=5>SUB TOTAL</th>
				<th class='text-right text-danger'>Rp. ".$this->func->formUang($totaldiskon)."</th>
				<th class='text-right text-success'>Rp. ".$this->func->formUang($totalongkir)."</th>
				<th class='text-right text-success'>Rp. ".$this->func->formUang($total)."</th>
			</tr>
			<tr>
				<th class='text-right bg-light' colspan=7>GRAND TOTAL</th>
				<th class='text-right text-light bg-success'>Rp. ".$this->func->formUang($total-$totaldiskon)."</th>
			</tr>
			";
		}else{
			echo "<tr><td colspan=6 class='text-center text-danger'>Belum ada data</td></tr>";
		}
	?>
	</table>
</div>
<?php
    }else{
        echo '<div class="m-tb-40 text-center text-danger">PILIH PENGGUNA UNTUK MENAMPILKAN LAPORAN</div>';
    }
?>