# Contao extension: barcode

This extension for the Contao CMS allows developers to easily generate 
barcodes and QR codes. To improve the speed of the program the processing
itself is done in a native c binary. This removes memory spikes and speeds
up the generation of codes.

The c binary itself is GNU Barcode (https://www.gnu.org/software/barcode/)
and supports the following formats (synonyms appear on the same line):

```
"ean", "ean13", "ean-13", "ean8", "ean-8"
"upc", "upc-a", "upc-e"
"isbn"
"39", "code39"
"128c", "code128c"
"128b", "code128b"
"128", "code128"
"128raw"
"i25", "interleaved 2 of 5"
"cbr", "codabar"
"msi"
"pls", "plessey"
"code93", "93"
```
 
 The gnu barcode binary is packaged on all major linux distributions.
