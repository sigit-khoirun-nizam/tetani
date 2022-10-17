<div class="seksen-shadow"></div>
<div class="seksen">
	<div class="wrapper">
		<div class="divider"></div>
		<div id="pesan" class="pesan">
			<div class="kolom4 kontak-wrap hidesmall" id="pesanselek">
				<div class="title">
					<b>Pesan</b>
					<i id="loader" class="pullright fa fa-spin fa-sync"></i>
				</div>
				<div class="kontak">
					<?php
						//$this->db->where("usrid",$_SESSION["usrid"]);
						//$this->db->or_where("idtoko",$_SESSION["idtoko"]);
						//$this->db->group_by("usrid,idtoko");
						//$this->db->order_by("id","DESC");
						
						if(isset($toko) AND isset($produk)){
							$tokos = $this->func->getToko($toko,"semua","url");
							$pros = $this->func->getProduk($produk,"semua","url");
						}
						
						$db = $this->db->query("SELECT * FROM blw_pesan WHERE id IN (SELECT MAX(id) FROM blw_pesan GROUP BY usrid,idtoko) AND (usrid = '".$_SESSION["usrid"]."' OR idtoko = '".$_SESSION["idtoko"]."') ORDER BY id DESC");
						$i=1;
						
						$usridf = 0;
						$sayaf = 0;
						$idtokof = 0;
						$idfirst = 0;
						
						if($db->num_rows() > 0){
						foreach($db->result() as $res){
							$user = $this->func->getProfil($res->usrid,"semua","usrid");
							$toko = $this->func->getToko($res->idtoko,"semua");
							
							if($res->dari == 1 AND $res->idtoko == $_SESSION["idtoko"]){
								$nama = $this->func->potong($user->nama,10,"...");
								$profil = base_url("assets/img/profil/".$user->foto);
								$saya = 2; //TOKO
								$label = "<i class='label label-green'>pembeli</i>";
							}elseif($res->dari == 1 AND $res->usrid == $_SESSION["usrid"]){
								$nama = $this->func->potong(strtoupper($toko->nama),16,"...");
								$profil = base_url("assets/img/toko/".$toko->foto);
								$saya = 1; //PEMBELI
								$label = "<i class='label label-default'>penjual</i>";
							}elseif($res->dari == 2 AND $res->usrid == $_SESSION["usrid"]){
								$nama = $this->func->potong(strtoupper($toko->nama),16,"...");
								$profil = base_url("assets/img/toko/".$toko->foto);
								$saya = 1; //PEMBELI
								$label = "<i class='label label-default'>penjual</i>";
							}elseif($res->dari == 2 AND $res->idtoko == $_SESSION["idtoko"]){
								$nama = $this->func->potong(strtoupper($user->nama),16,"...");
								$profil = base_url("assets/img/toko/".$user->foto);
								$saya = 2; //TOKO
								$label = "<i class='label label-green'>pembeli</i>";
							}else{
								$nama = "error";
								$profil = base_url("assets/img/profil/default.jpg");
							}
							//for($is=0; $is<5; $is++){
					?>
						<div class="list<?php if($i<=1){ echo " active"; $idfirst = $res->id;$idtokof=$res->idtoko;$sayaf=$saya;$usridf=$res->usrid; } ?>" data-idtoko="<?php echo $res->idtoko; ?>" data-usrid="<?php echo $res->usrid; ?>" data-ads="<?php echo $res->id; ?>" data-saya="<?php echo $saya; ?>">
							<div class="foto" style="background-image: url('<?php echo $profil; ?>');"></div>
							<div class="nama">
								<b><?php echo $nama." ".$label; ?></b><br/>
								<small><i><?php echo $this->func->potong($res->isipesan,40,"..."); ?></i></small>
							</div>
							<div class="gatukan"></div>
						</div>
						<?php //}
						$i++;}
						}else{
							echo "<div class='list-default'>belum ada percakapan</div>";
						}
						?>
				</div>			
			</div>
			<div class="kolom8 pesanbody" id="pesanbody">
				<div class="pesanload" id="pesanload">
				
				</div>
				<div class="pesankirim">
					<form id="kirim">
						<input type="hidden" id="usrid" name="usrid" value="<?php echo $usridf; ?>" />
						<input type="hidden" id="idtoko" name="idtoko" value="<?php echo $idtokof; ?>" />
						<input type="hidden" id="dari" name="dari" value="<?php echo $sayaf; ?>" />
						<table class="table">
							<tr>
								<td>
									<div class="inner-addon left-addon">
										<span class="fa fa-paper-plane"></span>
										<input type="text" id="isipesan" name="isipesan" class="form-bordered" placeholder="ketik pesan..." />
									</div>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		var pesanHeight = $(window).height() - 160;
		var kontakHeight = pesanHeight - 60;
		$("#pesan").height(pesanHeight+"px");
		$("#pesanbody").height(pesanHeight+"px");
		$("#pesanselek").height(pesanHeight+"px");
		$(".kontak").height(kontakHeight+"px");
		
		$("#pesanload").load("<?php echo site_url("assync/pesanmasuk/?ads=".$idfirst); ?>",function(){
			$("#loader").hide();
		});
		
		$("#kirim").on("submit",function(e){
			e.preventDefault();
			var ads = $(".list.active").attr("data-ads");
			$("#loader").show();
			$.post("<?php echo site_url("assync/kirimpesan"); ?>",$(this).serialize(),function(msg){
				var data = eval("("+msg+")");
				if(data.success == true){
					$("#pesanload").load("<?php echo site_url("assync/pesanmasuk/"); ?>?ads="+ads,function(){
						$("#loader").hide();
						$("#isipesan").val("");
					});
				}else{
					alert("error, terjadi kesalahan teknis");
				}
			});
		});
		
		$(".list").each(function(){
			$(this).on("click",function(){
				var ads = $(this).attr("data-ads");
				var idtoko = $(this).attr("data-idtoko");
				var usrid = $(this).attr("data-usrid");
				var dari = $(this).attr("data-saya");
				$("#pesanload").html("");
				$("#loader").show();
				$(".kontak .active").removeClass("active");
				$(this).addClass("active");
				$("#pesanload").load("<?php echo site_url("assync/pesanmasuk/"); ?>?ads="+ads,function(){
					$("#loader").hide();
					$("#idtoko").val(idtoko);
					$("#dari").val(dari);
					$("#usrid").val(usrid);
					$("#isipesan").val("");
				});
			});
		});
	});
</script>
		