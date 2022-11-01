<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

    <div id="wrapper">

        <div id="content-wrapper">

            <div class="container-fluid">

                <a href="<?php echo site_url('datatransporter') ?>" class="btn btn-danger float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
                <h4 class="page-title">Edit Petani</h4>

                <div class="card mb-3">
                    <div class="card-body">

                        <?php
                        //create form
                        $attributes = array('id' => 'FrmEditMahasiswa', 'method' => "post", "autocomplete" => "off");
                        echo form_open('', $attributes);
                        ?>

                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="hidden" class="form-control" id="id" name="id" value=" <?= $data_transporter->id; ?>">
                            <input type="text" class="form-control" id="nama" name="nama" value=" <?= $data_transporter->nama; ?>">
                            <small class="text-danger">
                                <?php echo form_error('nama') ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="no hp">No Hp</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value=" <?= $data_transporter->no_hp; ?>">
                            <small class="text-danger">
                                <?php echo form_error('no_hp') ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email" value=" <?= $data_transporter->email; ?>">
                            <small class="text-danger">
                                <?php echo form_error('email') ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= $data_transporter->alamat; ?></textarea>
                            <small class="text-danger">
                                <?php echo form_error('alamat') ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="provinsi">Provinsi</label>
                            <input type="text" class="form-control" id="provinsi" name="provinsi" value=" <?= $data_transporter->provinsi; ?>">
                            <small class="text-danger">
                                <?php echo form_error('provinsi') ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="kota">Kota</label>
                            <input type="text" class="form-control" id="kota" name="kota" value=" <?= $data_transporter->kota; ?>">
                            <small class="text-danger">
                                <?php echo form_error('kota') ?>
                            </small>
                        </div>


                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="Pilih" selected disabled>Pilih</option>
                                <option value="Aktif" <?php if ($data_transporter->status == "Aktif") : echo "selected";
                                                        endif; ?>>Aktif</option>
                                <option value="Tidak Aktif" <?php if ($data_transporter->status == "Tidak Aktif") : echo "selected";
                                                            endif; ?>>Tidak Aktif</option>
                            </select>
                            <small class="text-danger">
                                <?php echo form_error('Agama') ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="account bank">Account Bank</label>
                            <input type="text" class="form-control" id="account_bank" name="account_bank" value=" <?= $data_transporter->account_bank; ?>">
                            <small class="text-danger">
                                <?php echo form_error('account_bank') ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="rekening bank">Rekening Bank</label>
                            <input type="text" class="form-control" id="rekening_bank" name="rekening_bank" value=" <?= $data_transporter->rekening_bank; ?>">
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
