/**
 * countdown.js
 *
 * countdown
 */

jQuery(document).ready(($) => {
  function formatIntervalRaw(parts) {
    return JSON.stringify(parts);
  }

  function formatInterval(ms,_format) {
    const format = _format || "raw";
    const s = Math.floor(ms / 1000);

    const units = [
      [86400,"d"],
      [3600,"h"],
      [60,"m"],
      [1,"s"]
    ];

    let ns = s;
    const parts = {};
    let found = false;
    for (let i = 0;i < units.length;++i) {
      const unit = units[i];
      const q = Math.floor(ns / unit[0]);

      if (q > 0 || found) {
        parts[unit[1]] = q;
        found = true;
      }

      ns %= unit[0];
    }

    if (format == "raw") {
      return formatIntervalRaw(parts);
    }

    return formatIntervalRaw(parts);
  }

  function makeTimerCallback(info) {
    function timerCallback() {
      const now = Date.now();
      const ts = info.dt.getTime();

      if (now > ts) {
        info.$elem.text(formatInterval(0));
        window.clearInterval(info.intervalId);
        return;
      }

      info.$elem.text(formatInterval(ts - now));
    }

    return timerCallback;
  }

  function prepareCountdown(index) {
    const $elem = $(this);
    const dateString = $elem.attr("data-countdown-date");
    const dt = new Date();

    const ts = Date.parse(dateString);
    if (isNaN(ts)) {
      return;
    }

    dt.setTime(ts);
    $elem.text(formatInterval(0));

    const info = {
      $elem,
      dt
    };

    const callback = makeTimerCallback(info);
    info.intervalId = window.setInterval(callback,1000);
  }

  const elems = $("[data-countdown-date]");
  elems.each(prepareCountdown);
});
