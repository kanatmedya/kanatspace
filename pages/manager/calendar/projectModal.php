<div id="projectModal" class="modal hidden">
    <div class="modal-content">
        <div class="mb-4">
            <span class="close" onclick="closeProjectModal()">&times;</span>
            <h2>Proje Ekle/Düzenle</h2>
        </div>
        <form id="projectForm">
            <input type="hidden" id="editProjectId" name="id">
            <div class="panel grid grid-rows gap-4 px-6 pt-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2">
                    <!-- Proje Başlığı -->
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p>Proje Başlığı</p>
                            <input name="title" id="editProjectTitle" required type="text" class="form-input" />
                        </div>
                    </div>

                    <!-- Proje Türü -->
                    <div>
                        <p>Proje Türü</p>
                        <select name="projectType" id="editProjectType" required class="form-select text-white-dark">
                            <option value="">Proje Türü Seçiniz</option>
                        </select>
                    </div>
                </div>

                <!-- Teslim Tarihi -->
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p>Teslim Tarihi</p>
                        <div class="flex">
                            <input type="date" id="editDeadlineDate" name="deadlineDate" class="form-input" />
                            <input type="time" id="editDeadlineTime" name="deadlineTime" class="form-input" />
                        </div>
                    </div>
                </div>

                <!-- Müşteri Seçimi -->
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p>Müşteri</p>
                        <select name="client" id="editProjectClient" required class="form-select text-white-dark">
                            <option value="">Müşteri Seçiniz</option>
                        </select>
                    </div>
                </div>

                <!-- Durum Seçimi -->
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p>Proje Durumu</p>
                        <select name="status" id="editProjectStatus" required class="form-select text-white-dark">
                            <option value="Proje">Proje</option>
                            <option value="Beklemede">Beklemede</option>
                            <option value="Devam Eden">Devam Eden</option>
                            <option value="Onayda">Onayda</option>
                            <option value="Onaylandı">Onaylandı</option>
                            <option value="Tamamlandı">Tamamlandı</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-full gap-2 mt-5">Kaydet</button>
            <button type="button" id="deleteProjectButton" class="btn btn-danger w-full gap-2 mt-5">Projeyi Sil</button>
        </form>
    </div>
</div>
