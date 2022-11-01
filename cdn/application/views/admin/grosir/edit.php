<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

	<div id="wrapper">

		<div id="content-wrapper">

			<div class="container-fluid">

				<a href="<?php echo site_url('grosir') ?>" class="btn btn-danger float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
				<h4 class="page-title">Edit Grosir</h4>

				<div class="card mb-3">
					<div class="card-body">

						<?php
	                    //create form
	                    $attributes = array('id' => 'FrmEditMahasiswa', 'method' => "post", "autocomplete" => "off");
	                    echo form_open('', $attributes);
	                    ?>

	                    	<div class="form-group">
								<label for="kode_grosir">Kode Grosir</label>
								<input type="hidden" class="form-control" id="id_grosir" name="id_grosir" value=" <?= $data_grosir->id_grosir; ?>">
								<input class="form-control"	type="text" name="kode_grosir" id="kode_grosir" value=" <?= $data_grosir->kode_grosir; ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('kode_grosir') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="nama_grosir">Nama</label>
								<input class="form-control"	type="text" name="nama_grosir" id="nama_grosir" value=" <?= $data_grosir->nama_grosir; ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('nama_grosir') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="no_hp">Nomor HP</label>
								<input class="form-control" type="text" name="no_hp" id="no_hp" value=" <?= $data_grosir->no_hp; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('no_hp') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="email">Email</label>
								<input class="form-control" type="text" name="email" id="email" value=" <?= $data_grosir->email; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('email') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="alamat">Alamat</label>
								<input class="form-control" type="text" name="alamat" id="alamat" value=" <?= $data_grosir->alamat; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('alamat') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="kota">Kota</label>
								<input class="form-control" type="text" name="kota" id="kota" value=" <?= $data_grosir->kota; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('kota') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="provinsi">Provinsi</label>
								<input class="form-control" type="text" name="provinsi" id="provinsi" value=" <?= $data_grosir->provinsi; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('provinsi') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="kategori_produk">Kategori Produk</label>
									<!-- <input type="checkbox" name="Kategori_produk" value="Buah" />Buah <br>
									<input type="checkbox" name="Kategori_produk" value="Hasil Kebun" />Hasil Kebun <br>
									<input type="checkbox" name="Kategori_produk" value="Rempah" />Rempah <br>
									<input type="checkbox" name="Kategori_produk" value="Semuanya" />Semuanya <br> <br> -->
								<input class="form-control" type="text" name="kategori_produk" id="kategori_produk" value=" <?= $data_grosir->kategori_produk; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('kategori_produk') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="status">Status</label>
									<select class="form-control col-md-6" name="status" id="status" value=" <?= $data_grosir->status; ?>" required >
										<option value="Aktif">Aktif</option>
										<option value="Tidak Aktif">Tidak Aktif</option>
									</select> <br>
								<small class="text-danger">
	                                <?php echo form_error('status') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="nama_bank">Account Bank</label>
								<input class="form-control" type="text" name="nama_bank" id="nama_bank" value=" <?= $data_grosir->nama_bank; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('nama_bank') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="rekening_bank">Rekening Bank</label>
								<input class="form-control" type="text" name="rekening_bank" id="rekening_bank" value=" <?= $data_grosir->rekening_bank; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('rekening_bank') ?>
	                            </small>
							</div>

							<div class="card-body">
								<button type="submit" class="btn btn-primary"><i class="la la-check-circle"></i> Update</button>
							</div>
						</form>

					</div>

				</div>
				<!-- /.container-fluid -->

				<!-- Sticky Footer -->

			</div>
			<!-- /.content-wrapper -->

		</div>
		<!-- /#wrapper -->

</body>

</html>