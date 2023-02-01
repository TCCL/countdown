/**
 * countdown.js
 *
 * countdown
 */

jQuery(document).ready(($) => {
  const DEFAULT_SETTINGS = {
    format: "raw",
    include_seconds: true
  };

  function formatIntervalRaw(parts,settings) {
    const copy = Object.assign({},parts);
    if (!settings.include_seconds) {
      delete copy["s"];
    }
    return JSON.stringify(copy);
  }

  function formatIntervalClock(parts,settings) {
    const order = "dhms";

    let i = 0;
    let n = order.length;
    let prefix = "";
    if ("d" in parts) {
      prefix = "+";
    }
    else {
      i = 1;
    }

    if (!settings.include_seconds) {
      n -= 1;
    }

    const components = [];
    for (;i < n;++i) {
      const t = parts[order[i]] || 0;
      components.push(t.toString().padStart(2,"0"));
    }

    return prefix + components.join(":");
  }

  function formatIntervalWords(parts,settings) {
    const order = [
      ["d","day"],
      ["h","hour"],
      ["m","minute"],
      ["s","second"]
    ];

    let i = 0;
    let n = order.length;

    if (!settings.include_seconds) {
      n -= 1;
    }

    const components = [];
    for (;i < n;++i) {
      const t = parts[order[i][0]] || 0;
      components.push(t.toString() + " " + order[i][1] + (t>1 ? "s" : ""));
    }

    return components.join(", ");
  }

  function formatInterval(ms,settings) {
    const format = settings.format || "raw";
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
      return formatIntervalRaw(parts,settings);
    }
    if (format == "clock") {
      return formatIntervalClock(parts,settings);
    }
    if (format == "words") {
      return formatIntervalWords(parts,settings);
    }

    return formatIntervalRaw(parts,settings);
  }

  function makeTimerCallback(info) {
    function timerCallback() {
      const now = Date.now();
      const ts = info.dt.getTime();

      if (now > ts) {
        info.$elem.text(formatInterval(0,info.settings));
        window.clearInterval(info.intervalId);
        return;
      }

      info.$elem.text(formatInterval(ts - now,info.settings));
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

    let settings;
    const settingsRaw = $elem.attr("data-countdown-settings");
    if (settingsRaw) {
      settings = JSON.parse(settingsRaw);
    }
    else {
      // Apply default settings.
      settings = {};
    }
    settings = Object.assign({},DEFAULT_SETTINGS,settings);

    dt.setTime(ts);
    $elem.text(formatInterval(0,settings));

    const info = {
      $elem,
      dt,
      settings
    };

    const callback = makeTimerCallback(info);
    info.intervalId = window.setInterval(callback,1000);
  }

  const elems = $("[data-countdown-date]");
  elems.each(prepareCountdown);
});
