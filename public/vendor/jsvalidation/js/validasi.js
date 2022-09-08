$(document).ready(function() {
	jQuery.validator.addMethod("angka", function(value, element) {
		angka_valid = /^\d*$/.test(value);
		return this.optional(element) || angka_valid;
	}, "Harus Berisi Angka");

	jQuery.validator.addMethod("nama", function(value, element) {
		valid = /^[a-zA-Z '\.,\-]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip");

	jQuery.validator.addMethod("alfanumerik", function(value, element) {
		valid = /^[a-zA-Z0-9 ]+$/i.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alfanumerik");

	jQuery.validator.addMethod("nomor_sk", function(value, element) {
		valid = /^[a-zA-Z0-9 \.\-\/]+$/i.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alfanumerik, spasi, titik, garis miring dan strip");

	jQuery.validator.addMethod("bilangan", function(value, element) {
		valid = /^[0-9]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter numerik");

	jQuery.validator.addMethod("alamat", function(value, element) {
		valid = /^[a-zA-Z0-9 '\.,\-\/]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alpha, numerik, spasi, titik, koma, strip, tanda petik dan garis miring");

	jQuery.validator.addMethod("username", function(value, element) {
		valid = /^[a-zA-Z0-9]{4,30}$/.test(value);
		return this.optional(element) || valid;
	}, "Username hanya boleh berisi karakter alpha, numerik dan terdiri dari 4 hingga 30 karakter");
})
