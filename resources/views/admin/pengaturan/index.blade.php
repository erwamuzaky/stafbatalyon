<x-admin-layout title="Pengaturan Halaman Depan">
    <x-card title="Pengaturan Halaman Depan" class="max-w-4xl">
        <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="mt-6 space-y-8"
              x-data="{ memproses: false }" @submit="memproses = true"
              enctype="multipart/form-data">
            @csrf
            @method('patch')

            <!-- Identitas -->
            <div class="space-y-6">
                <h3 class="font-judul text-lg font-semibold text-laut-800">Identitas</h3>

                <div>
                    <x-input-label for="situs_nama" value="Nama Situs" />
                    <x-text-input id="situs_nama" name="situs_nama" type="text" class="mt-1 block w-full"
                                  :value="old('situs_nama', $pengaturan['situs_nama'])" />
                    <x-input-error :messages="$errors->get('situs_nama')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="situs_sekolah" value="Nama Sekolah" />
                    <x-text-input id="situs_sekolah" name="situs_sekolah" type="text" class="mt-1 block w-full"
                                  :value="old('situs_sekolah', $pengaturan['situs_sekolah'])" />
                    <x-input-error :messages="$errors->get('situs_sekolah')" class="mt-2" />
                </div>
            </div>

            <!-- Hero (banner halaman depan) -->
            <div class="space-y-6">
                <h3 class="font-judul text-lg font-semibold text-laut-800">Hero (banner halaman depan)</h3>

                <div>
                    <x-input-label for="hero_label" value="Label Hero" />
                    <x-text-input id="hero_label" name="hero_label" type="text" class="mt-1 block w-full"
                                  :value="old('hero_label', $pengaturan['hero_label'])" />
                    <x-input-error :messages="$errors->get('hero_label')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="hero_pengantar" value="Pengantar Hero" />
                    <textarea id="hero_pengantar" name="hero_pengantar" rows="3"
                              class="input-baku mt-1 block w-full"
                              >{{ old('hero_pengantar', $pengaturan['hero_pengantar']) }}</textarea>
                    <x-input-error :messages="$errors->get('hero_pengantar')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="hero_background" value="Background Hero (Maksimal 4 MB)" />
                    <div class="mt-1">
                        @if($heroBackground)
                            <div class="mb-2">
                                <img src="{{ $heroBackground }}" alt="Background Hero" class="max-w-xs rounded border">
                            </div>
                        @endif
                        <input type="file" id="hero_background" name="hero_background" accept="image/*"
                               class="input-baku block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-laut-50 file:text-laut-700 hover:file:bg-laut-100">
                    </div>
                    <x-input-error :messages="$errors->get('hero_background')" class="mt-2" />
                </div>

                @if($heroBackground)
                    <div class="flex items-center">
                        <input type="hidden" name="hapus_background" value="0">
                        <input type="checkbox" id="hapus_background" name="hapus_background" value="1"
                               class="rounded border-gray-300 text-laut-600 shadow-sm focus:ring-laut-500">
                        <label for="hapus_background" class="ml-2 text-sm text-gray-600">Hapus background saat ini</label>
                    </div>
                @endif
            </div>

            <!-- Tentang -->
            <div class="space-y-6">
                <h3 class="font-judul text-lg font-semibold text-laut-800">Tentang</h3>

                <div>
                    <x-input-label for="tentang_label" value="Label Tentang" />
                    <x-text-input id="tentang_label" name="tentang_label" type="text" class="mt-1 block w-full"
                                  :value="old('tentang_label', $pengaturan['tentang_label'])" />
                    <x-input-error :messages="$errors->get('tentang_label')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="tentang_judul" value="Judul Tentang" />
                    <x-text-input id="tentang_judul" name="tentang_judul" type="text" class="mt-1 block w-full"
                                  :value="old('tentang_judul', $pengaturan['tentang_judul'])" />
                    <x-input-error :messages="$errors->get('tentang_judul')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="tentang_pengantar" value="Pengantar Tentang" />
                    <textarea id="tentang_pengantar" name="tentang_pengantar" rows="3"
                              class="input-baku mt-1 block w-full"
                              >{{ old('tentang_pengantar', $pengaturan['tentang_pengantar']) }}</textarea>
                    <x-input-error :messages="$errors->get('tentang_pengantar')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <x-input-label for="tentang_fitur1_judul" value="Fitur 1 - Judul" />
                        <x-text-input id="tentang_fitur1_judul" name="tentang_fitur1_judul" type="text"
                                      class="mt-1 block w-full"
                                      :value="old('tentang_fitur1_judul', $pengaturan['tentang_fitur1_judul'])" />
                        <x-input-error :messages="$errors->get('tentang_fitur1_judul')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tentang_fitur1_teks" value="Fitur 1 - Teks" />
                        <textarea id="tentang_fitur1_teks" name="tentang_fitur1_teks" rows="2"
                                  class="input-baku mt-1 block w-full"
                                  >{{ old('tentang_fitur1_teks', $pengaturan['tentang_fitur1_teks']) }}</textarea>
                        <x-input-error :messages="$errors->get('tentang_fitur1_teks')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <x-input-label for="tentang_fitur2_judul" value="Fitur 2 - Judul" />
                        <x-text-input id="tentang_fitur2_judul" name="tentang_fitur2_judul" type="text"
                                      class="mt-1 block w-full"
                                      :value="old('tentang_fitur2_judul', $pengaturan['tentang_fitur2_judul'])" />
                        <x-input-error :messages="$errors->get('tentang_fitur2_judul')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tentang_fitur2_teks" value="Fitur 2 - Teks" />
                        <textarea id="tentang_fitur2_teks" name="tentang_fitur2_teks" rows="2"
                                  class="input-baku mt-1 block w-full"
                                  >{{ old('tentang_fitur2_teks', $pengaturan['tentang_fitur2_teks']) }}</textarea>
                        <x-input-error :messages="$errors->get('tentang_fitur2_teks')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <x-input-label for="tentang_fitur3_judul" value="Fitur 3 - Judul" />
                        <x-text-input id="tentang_fitur3_judul" name="tentang_fitur3_judul" type="text"
                                      class="mt-1 block w-full"
                                      :value="old('tentang_fitur3_judul', $pengaturan['tentang_fitur3_judul'])" />
                        <x-input-error :messages="$errors->get('tentang_fitur3_judul')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tentang_fitur3_teks" value="Fitur 3 - Teks" />
                        <textarea id="tentang_fitur3_teks" name="tentang_fitur3_teks" rows="2"
                                  class="input-baku mt-1 block w-full"
                                  >{{ old('tentang_fitur3_teks', $pengaturan['tentang_fitur3_teks']) }}</textarea>
                        <x-input-error :messages="$errors->get('tentang_fitur3_teks')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Struktur Staf -->
            <div class="space-y-6">
                <h3 class="font-judul text-lg font-semibold text-laut-800">Struktur Staf</h3>

                <div>
                    <x-input-label for="struktur_judul" value="Judul Struktur" />
                    <x-text-input id="struktur_judul" name="struktur_judul" type="text" class="mt-1 block w-full"
                                  :value="old('struktur_judul', $pengaturan['struktur_judul'])" />
                    <x-input-error :messages="$errors->get('struktur_judul')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="struktur_subjudul" value="Subjudul Struktur" />
                    <textarea id="struktur_subjudul" name="struktur_subjudul" rows="2"
                              class="input-baku mt-1 block w-full"
                              >{{ old('struktur_subjudul', $pengaturan['struktur_subjudul']) }}</textarea>
                    <x-input-error :messages="$errors->get('struktur_subjudul')" class="mt-2" />
                </div>
            </div>

            <!-- Bidang -->
            <div class="space-y-6">
                <h3 class="font-judul text-lg font-semibold text-laut-800">Bidang</h3>

                <div>
                    <x-input-label for="bidang_judul" value="Judul Bidang" />
                    <x-text-input id="bidang_judul" name="bidang_judul" type="text" class="mt-1 block w-full"
                                  :value="old('bidang_judul', $pengaturan['bidang_judul'])" />
                    <x-input-error :messages="$errors->get('bidang_judul')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="bidang_subjudul" value="Subjudul Bidang" />
                    <textarea id="bidang_subjudul" name="bidang_subjudul" rows="2"
                              class="input-baku mt-1 block w-full"
                              >{{ old('bidang_subjudul', $pengaturan['bidang_subjudul']) }}</textarea>
                    <x-input-error :messages="$errors->get('bidang_subjudul')" class="mt-2" />
                </div>
            </div>

            <!-- Ajakan Daftar -->
            <div class="space-y-6">
                <h3 class="font-judul text-lg font-semibold text-laut-800">Ajakan Daftar</h3>

                <div>
                    <x-input-label for="ajakan_judul" value="Judul Ajakan" />
                    <x-text-input id="ajakan_judul" name="ajakan_judul" type="text" class="mt-1 block w-full"
                                  :value="old('ajakan_judul', $pengaturan['ajakan_judul'])" />
                    <x-input-error :messages="$errors->get('ajakan_judul')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="ajakan_teks" value="Teks Ajakan" />
                    <textarea id="ajakan_teks" name="ajakan_teks" rows="3"
                              class="input-baku mt-1 block w-full"
                              >{{ old('ajakan_teks', $pengaturan['ajakan_teks']) }}</textarea>
                    <x-input-error :messages="$errors->get('ajakan_teks')" class="mt-2" />
                </div>
            </div>

            <!-- Footer -->
            <div class="space-y-6">
                <h3 class="font-judul text-lg font-semibold text-laut-800">Footer</h3>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <x-input-label for="footer_nama" value="Nama Footer" />
                        <x-text-input id="footer_nama" name="footer_nama" type="text" class="mt-1 block w-full"
                                      :value="old('footer_nama', $pengaturan['footer_nama'])" />
                        <x-input-error :messages="$errors->get('footer_nama')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="footer_sekolah" value="Sekolah Footer" />
                        <x-text-input id="footer_sekolah" name="footer_sekolah" type="text" class="mt-1 block w-full"
                                      :value="old('footer_sekolah', $pengaturan['footer_sekolah'])" />
                        <x-input-error :messages="$errors->get('footer_sekolah')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="footer_peta_kata_kunci" value="Kata Kunci Footer" />
                        <x-text-input id="footer_peta_kata_kunci" name="footer_peta_kata_kunci" type="text"
                                      class="mt-1 block w-full"
                                      :value="old('footer_peta_kata_kunci', $pengaturan['footer_peta_kata_kunci'])" />
                        <x-input-error :messages="$errors->get('footer_peta_kata_kunci')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="footer_peta_catatan" value="Catatan Footer" />
                        <x-text-input id="footer_peta_catatan" name="footer_peta_catatan" type="text"
                                      class="mt-1 block w-full"
                                      :value="old('footer_peta_catatan', $pengaturan['footer_peta_catatan'])" />
                        <x-input-error :messages="$errors->get('footer_peta_catatan')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="footer_hak_cipta" value="Hak Cipta Footer" />
                        <x-text-input id="footer_hak_cipta" name="footer_hak_cipta" type="text" class="mt-1 block w-full"
                                      :value="old('footer_hak_cipta', $pengaturan['footer_hak_cipta'])" />
                        <x-input-error :messages="$errors->get('footer_hak_cipta')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-4">
                <x-primary-button x-bind:disabled="memproses">
                    <span x-show="!memproses">Simpan pengaturan</span>
                    <span x-show="memproses" x-cloak>Menyimpan...</span>
                </x-primary-button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sekunder">Batal</a>
            </div>
        </form>
    </x-card>
</x-admin-layout>