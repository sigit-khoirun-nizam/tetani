<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col">Tanggal</th>
			<th scope="col">No Invoice</th>
			<th scope="col">Nama Pembeli</th>
			<th scope="col">Total</th>
			<th scope="col">Kode Bayar</th>
			<th scope="col">Kurir</th>
			<th scope="col">Aksi</th>
		</tr>
		<?php
			$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
			$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
			$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
			$perpage = 10;

			$where = "(invoice LIKE '%$cari%' OR total LIKE '%$cari%' OR kodebayar LIKE '%$cari%') AND status = 0";
			$this->db->where($where);
			$rows = $this->db->get("pembayaran");
			$rows = $rows->num_rows();

			$this->db->from('pembayaran');
			$this->db->where($where);
			$this->db->order_by($orderby,"desc");
			$this->db->limit($perpage,($page-1)*$perpage);
			$pro = $this->db->get();
			
			if($rows > 0){
				$no = 1;
				foreach($pro->result() as $r){
					$bukti = "";
					$trx = $this->func->getTransaksi($r->id,"semua","idbayar");
					$kurir = strtoupper($trx->kurir." ".$trx->paket);
					$tgl = $this->func->ubahTgl("d M Y H:i",$r->tgl);
					$this->db->where("idbayar",$r->id);
					$dbs = $this->db->get("konfirmasi");
					if($dbs->num_rows() > 0){
						foreach($dbs->result() as $res){
							$bukti = $res->bukti;
							$tgl .= "<br/><a href='javascript:void(0)' onclick='bukti(\"".base_url("konfirmasi/".$res->bukti)."\")'>&raquo; Lihat Bukti Transfer</a>";
						}
					}
					$img = ($r->ipaymu != "") ? "<img style='height:12px;' src='".base_url("assets/img/ipaymu.png")."'>" : "";
					$img = ($r->midtrans_id != "") ? "<img style='height:12px;' src='".base_url("assets/img/midtrans.png")."'>" : $img;
					$trxid = $this->func->getTransaksi($r->id,"id","idbayar");
		?>
			<tr>
				<td class="text-center"><i class="fas fa-circle text-danger blink"></i> &nbsp; <?=$tgl;?></td>
				<td><?=$r->invoice?> &nbsp; <?php echo $img; ?></td>
				<td><?=$this->func->getProfil($r->usrid,"nama","usrid")?></td>
				<td><?=$this->func->formUang($r->total-$r->kodebayar)?></td>
				<td><?=$this->func->formUang($r->kodebayar)?></td>
				<td><?=$kurir?></td>
				<td style="min-width:220px">
					<?php if($r->ipaymu == "" OR $bukti != ""){?><a href="javascript:konfirm(<?=$r->id?>)" class="btn btn-success btn-xs"><i class="fas fa-check"></i> Verifikasi</a><?php } ?>
					<a href="javascript:detail(<?=$trxid?>)" class="btn btn-primary btn-xs"><i class="fas fa-list"></i> Detail</a>
					<?php if($r->ipaymu == ""){?><a href="javascript:batalin(<?=$r->id?>)" class="btn btn-danger btn-xs"><i class="fas fa-times"></i> Batalkan</a><?php } ?>
				</td>
			</tr>
		<?php	
					$no++;
				}
			}else{
				echo "<tr><td colspan=7 class='text-center text-danger'>Belum ada pesanan</td></tr>";
			}
		?>
	</table>

	<?=$this->func->createPagination($rows,$page,$perpage,"loadBayar");?>
</div>

<script type="text/javascript">	
	function konfirm(id){
		swal.fire({
			title: "Perhatian!",
			text: "pastikan uang sudah benar-benar masuk/ditranfer, lebih baik cek kembali mutasi.",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal"
		}).then((val)=>{
			loadingDulu();
			if(val.value){
				$.post("<?=site_url("api/updatepesanan")?>",{"id":id,"statusbayar":1,[$("#names").val()]:$("#tokens").val()},function(e){
					var data = eval("("+e+")");
					updateToken(data.token);
					if(data.success == true){
						swal.fire("Berhasil!","Pesanan siap untuk segera dikirim","success");
						loadBayar(1);
					}else{
						swal.fire("Gagal!","Terjadi kendala saat mengupdate data, cobalah beberapa saat lagi","error");
					}
				});
			}
		});
	}
	function batalin(id){
		swal.fire({
			title: "Perhatian!",
			text: "pesanan akan dibatalkan dan stok akan bertambah kembali.",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Tidak Jadi"
		}).then((val)=>{
			loadingDulu();
			if(val.value){
				$.post("<?=site_url('api/batalkanpesanan')?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(e){
					var data = eval("("+e+")");
					updateToken(data.token);
					if(data.success == true){
						swal.fire("Berhasil!","Pesanan telah dibatalkan","success");
						loadBayar(1);
					}else{
						swal.fire("Gagal!","Terjadi kendala saat mengupdate data, cobalah beberapa saat lagi","error");
					}
				});
			}
		});
	}
	
	function bukti(url){
		$("#bukti").attr("src",url);
		$("#modalbukti").modal();
	}
</script>

<div class="modal fade" id="modalbukti" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<img id="bukti" src="<?=base_url('assets/img/no-image.png')?>" style='width:100%;' />
		</div>
	</div>
</div>
