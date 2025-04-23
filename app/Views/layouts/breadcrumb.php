<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
            <h5 class="m-b-10"><?= esc($breadcrumb_item_active ?? '') ?></h5>
        </div>
        <ul class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li> -->
            <li class="breadcrumb-item"><a href="javascript:void(0)"><?= esc($breadcrumb_item ?? '') ?></a></li>
            <li class="breadcrumb-item" aria-current="page"><?= esc($breadcrumb_item_active ?? '') ?></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->
