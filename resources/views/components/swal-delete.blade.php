window.livewire.on('destroy', elemenId => {
Swal.fire({
title: 'Apakah anda yakin?',
text: 'Data tidak bisa dikembalikan!',
icon: "warning",
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#d33',
confirmButtonText: 'Hapus!'
}).then((result) => {
if (result.value) {
@this.call('destroy',elemenId)

}
});
});

window.livewire.on('deleteSelected', elemenId => {
Swal.fire({
title: 'Apakah anda yakin?',
text: 'Menghapus data yang terpilih!',
icon: "warning",
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#d33',
confirmButtonText: 'Hapus!'
}).then((result) => {
if (result.value) {
@this.call('deleteSelected')

}
});
});
