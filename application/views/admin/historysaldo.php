<?php
  if($saldo->num_rows() > 0){
?>
  <div class="table-list">
        <?php
          foreach($saldo->result() as $res){
            $old = ($res->darike != 2) ? "[invoice]" : "[rekening]";
            switch($res->darike){
              case '1':
                $new = $this->func->getTransaksi($res->sambung,"orderid");
              break;
              case '2':
                $new = $this->func->getSaldotarik($res->sambung,"idrek");
                $new = $this->func->getRekening($new,"semua");
                $bank = $this->func->getBank($new->idbank,"nama");
                $new = $bank." a/n ".$new->atasnama." (".$new->norek.")";
              break;
              case '3':
                $new = $this->func->getBayar($res->sambung,"invoice");
              break;
              case '4':
                $new = $this->func->getTransaksi($res->sambung,"orderid");
              break;
              default:
                $new = "";
              break;
            }
            $status = ($res->darike == 2) ? $this->func->getSaldotarik($res->sambung,"status") : 1;
            $status = ($status == 1) ? "<span class='text-success'>Berhasil</span>" : "<span class='text-danger'>Sedang Diproses</span>";
            $jumlah = $this->func->formUang($res->jumlah);
            $jumlah = ($res->darike != 2 AND $res->darike != 3) ? "<span class='text-success'>Rp ".$jumlah."</span>" : "<span class='text-danger'>Rp ".$jumlah."</span>";
        ?>
        <div class="table-item">
          <div class="row">
            <div class="col-md-3">
              <p><?php echo $this->func->ubahTgl("d M Y H:i",$res->tgl); ?></p>
            </div>
            <div class="col-md-3">
              <p><?php echo str_replace($old,$new,$this->func->getSaldodarike($res->darike,"keterangan")); ?></p>
            </div>
            <div class="col-md-2">
              <?php echo $status; ?>
            </div>
            <div class="col-md-2 font-bold text-dark">
              Rp &nbsp;<p><?php echo $jumlah; ?></p>
            </div>
            <div class="col-md-2 text-right">
              <p>Rp <?php echo $this->func->formUang($res->saldoakhir); ?></p>
            </div>
          </div>
        </div>
        <?php
          }
        ?>
  </div>
<?php
    echo $this->func->createPagination($rows,$page,$perpage,"historySaldo");
  }else{
    echo "
      <div class='w-full text-center section p-tb-30 m-t-10'>
        <i class='fas fa-exchange-alt fs-40 m-b-10 text-danger'></i><br/>
        <h5>BELUM ADA TRANSAKSI</h5>
      </div>
    ";
  }
?>
