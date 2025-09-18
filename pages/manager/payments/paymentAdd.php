<style>
    .sweetDanger {
  padding: 10px;
  font-size: 14px;
font-family: inherit;
color: #e63c11;
margin-top: 10px;
}
</style>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <form method="post">
        <div class="panel grid grid-rows gap-4 px-6 pt-6">
            <p>Ödeme Bilgileri</p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4">
                <!-- Tutar -->
                <div class="flex">
                    <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                        Tutar
                    </div>
                    <input name="amount" type="text" placeholder="" class="form-input rounded-none rounded-tr-md rounded-br-md" required/>
                </div>

                <!-- Döviz -->
                <div class="flex">
                    <div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 font-semibold border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">
                        Döviz
                    </div>
                    <select name="cc" class="form-select rounded-none rounded-tr-md rounded-br-md" required>
                        <option selected value="₺">₺</option>
                        <option value="$">$</option>
                        <option value="€">€</option>
                    </select>
                </div>

                <!-- İşlem Türü -->
                <div class="flex">
                    <select name="type" class="form-select text-white-dark">
                        <option value="-1">İşlem Türü Seçiniz</option>
                        <option value="0">Gider</option>
                        <option value="1">Gelir</option>
                    </select>
                </div>

                <!-- İşlem Tarihi -->
                <div class="flex">
                    <input type="date" name="date" class="form-input rounded-none rounded-tl-md rounded-bl-md" value="<?php echo date('Y-m-d'); ?>" />
                </div>
            </div>

            <p>Plan Bilgileri</p>
            <!-- Taksit Sayısı ve Taksit Tutarı -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4">
                <!-- Tekrar Türü -->
                <div class="flex">
                    <select name="recurrence_period" class="form-select text-white-dark">
                        <option value="-1">Tekrar Türü Seçiniz</option>
                        <option value="taksit">Taksitli İşlem</option>
                        <option value="daily">Günlük Tekrar</option>
                        <option value="weekly">Haftalık Tekrar</option>
                        <option value="monthly">Aylık Tekrar</option>
                        <option value="yearly">Yıllık Tekrar</option>
                    </select>
                </div>
                
                <div id="installment_count-section" style="display:none;">
                    <input name="installment_count" type="number" placeholder="Taksit Sayısı" class="form-input rounded-none rounded-tr-md rounded-br-md" />
                </div>
                
                <div id="installment_amount-section" style="display:none;">
                    <input name="installment_amount" type="text" placeholder="Taksit Tutarı" class="form-input rounded-none rounded-tr-md rounded-br-md" readonly />
                </div>

                <div id="repeat-section" style="display:none;">
                    <input name="repeat_count" type="number" placeholder="Tekrar Sayısı" class="form-input rounded-none rounded-tr-md rounded-br-md" />
                </div>
            </div>

            <p class="mt-5" id="category-header">Kategori ve Hesap Bilgileri</p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4" id="category-section">
                <!-- Kategori -->
                <div>
                    <select name="category" class="form-select text-white-dark">
                        <option value="-1">Lütfen İşlem Türü Seçiniz</option>
                    </select>
                </div>

                <!-- Alt Kategori -->
                <div id="subcategory-section">
                    <select name="categorySub" class="form-select text-white-dark">
                        <option value="-1">Lütfen İşlem Türü Seçiniz</option>
                    </select>
                </div>

                <!-- Gönderici -->
                <div id="sender-section">
                    <select name="sender" class="form-select text-white-dark">
                        <option value="-1">Gönderici</option>
                    </select>
                </div>

                <!-- Alıcı -->
                <div id="receiver-section">
                    <select name="reciever" class="form-select text-white-dark">
                        <option value="-1">Alıcı</option>
                    </select>
                </div>
            </div>

            <div class="flex mt-5" id="description-section">
                <input name="description" type="text" placeholder="Açıklama" class="form-input rounded-none rounded-tr-md rounded-br-md" />
            </div>

            <div class="flex mt-5 w-100" id="description-section">
                <input style="width: 100%;margin-left:0" name="paymentPlanDescription" type="text" value="Ödeme planı bilgileri burada gösterilecek" readonly/>
            </div>

            <button type="submit" class="btn btn-success w-full gap-2 mt-5" id="saveOrderButton">
                Kaydet
            </button>
        </div>
    </form>
</div>


<script src="pages/manager/payments/paymentAdd.js"></script>
