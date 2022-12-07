Inputmask.extendAliases({
    rupiah: {
              prefix: "Rp. ",
              groupSeparator: ".",
              alias: "numeric",
              placeholder: "0",
              autoGroup: true,
              digits: 0,
              digitsOptional: false,
              clearMaskOnLostFocus: false
          }
  });

// Format mata uang.

$(".rupiah").inputmask({ alias : "rupiah", prefix: '' });