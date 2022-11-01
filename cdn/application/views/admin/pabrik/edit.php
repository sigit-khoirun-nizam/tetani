<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

	<div id="wrapper">

		<div id="content-wrapper">

			<div class="container-fluid">

				<a href="<?php echo site_url('pabrik') ?>" class="btn btn-danger float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
				<h4 class="page-title">Edit Pabrik</h4>

				<div class="card mb-3">
					<div class="card-body">

						<?php
	                    //create form
	                    $attributes = array('id' => 'FrmEditMahasiswa', 'method' => "post", "autocomplete" => "off");
	                    echo form_open('', $attributes);
	                    ?>

	                    	<div class="form-group">
								<label for="kode_pabrik">Kode Pabrik</label>
								<input type="hidden" class="form-control" id="id_pabrik" name="id_pabrik" value=" <?= $data_pabrik->id_pabrik; ?>">
								<input class="form-control"	type="text" name="kode_pabrik" id="kode_pabrik" value=" <?= $data_pabrik->kode_pabrik; ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('kode_pabrik') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="nama_pabrik">Nama</label>
								<input class="form-control"	type="text" name="nama_pabrik" id="nama_pabrik" value=" <?= $data_pabrik->nama_pabrik; ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('nama_pabrik') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="no_hp">Nomor HP</label>
								<input class="form-control" type="text" name="no_hp" id="no_hp" value=" <?= $data_pabrik->no_hp; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('no_hp') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="email">Email</label>
								<input class="form-control" type="text" name="email" id="email" value=" <?= $data_pabrik->email; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('email') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="alamat">Alamat</label>
								<input class="form-control" type="text" name="alamat" id="alamat" value=" <?= $data_pabrik->alamat; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('alamat') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="kota">Kota</label>
								<input class="form-control" type="text" name="kota" id="kota" value=" <?= $data_pabrik->kota; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('kota') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="provinsi">Provinsi</label>
								<input class="form-control" type="text" name="provinsi" id="provinsi" value=" <?= $data_pabrik->provinsi; ?>" />
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
								<input class="form-control" type="text" name="kategori_produk" id="kategori_produk" value=" <?= $data_pabrik->kategori_produk; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('kategori_produk') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="status">Status</label>
									<select class="form-control col-md-6" name="status" id="status" value=" <?= $data_pabrik->status; ?>" required >
										<option value="Aktif">Aktif</option>
										<option value="Tidak Aktif">Tidak Aktif</option>
									</select> <br>
								<small class="text-danger">
	                                <?php echo form_error('status') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="nama_bank">Account Bank</label>
								<input class="form-control" type="text" name="nama_bank" id="nama_bank" value=" <?= $data_pabrik->nama_bank; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('nama_bank') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="rekening_bank">Rekening Bank</label>
								<input class="form-control" type="text" name="rekening_bank" id="rekening_bank" value=" <?= $data_pabrik->rekening_bank; ?>" />
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