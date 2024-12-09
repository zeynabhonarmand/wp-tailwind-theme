function toFarsiNumerals(str,padding=0) {
  var str = str.toString();
  const numMap = {
    0: "۰",
    1: "۱",
    2: "۲",
    3: "۳",
    4: "۴",
    5: "۵",
    6: "۶",
    7: "۷",
    8: "۸",
    9: "۹",
  };

  str = str.replace(/\d/g, function (match) {
    return numMap[match];
  });

  if (padding>0)
  {
    str = str.padStart(padding, "۰");
  }
  return str;
}
function formatTime(seconds) {
  // Convert seconds to minutes
  const minutes = Math.floor(seconds / 60);

  // If 60 minutes or more, convert to hours
  if (minutes >= 60) {
    const hours = Math.floor(minutes / 60);
    return hours + " ساعت";
  } else {
    return minutes + "دقیقه";
  }
}

export { toFarsiNumerals, formatTime };
