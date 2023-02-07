/**
 * countdown.js
 *
 * countdown
 */

jQuery(document).ready(($) => {
  const DEFAULT_INLINE_SETTINGS = {
    format: "raw",
    include_seconds: true
  };

  const DEFAULT_WIDGET_SETTINGS = {
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
      components.push(t.toString() + " " + order[i][1] + (t!=1 ? "s" : ""));
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

    if (format == "_parts") {
      return parts;
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

  function makeTimerInlineCallback(info) {
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

    timerCallback();

    return timerCallback;
  }

  function prepareCountdownInline(index) {
    const $elem = $(this);
    const dateString = $elem.attr("data-countdown-inline");
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
    settings = Object.assign({},DEFAULT_INLINE_SETTINGS,settings);

    dt.setTime(ts);

    const info = {
      $elem,
      dt,
      settings
    };

    const callback = makeTimerInlineCallback(info);
    info.intervalId = window.setInterval(callback,1000);
  }

  function makeTimerWidgetCallback(info) {
    const $container = {
      "d": info.$elem.find("[data-countdown-days]"),
      "h": info.$elem.find("[data-countdown-hours]"),
      "m": info.$elem.find("[data-countdown-minutes]"),
      "s": info.$elem.find("[data-countdown-seconds]")
    };

    const $update = {
      "d": info.$elem.find("[data-countdown-value-days]"),
      "h": info.$elem.find("[data-countdown-value-hours]"),
      "m": info.$elem.find("[data-countdown-value-minutes]"),
      "s": info.$elem.find("[data-countdown-value-seconds]")
    };

    info.settings.format = "_parts";
    if (!info.settings.include_seconds) {
      $container["s"].hide();
      $update["s"] = null;
    }

    function updateFromParts(parts) {
      const order = "dhms";

      for (let i = 0;i < order.length;++i) {
        const $elem = $update[order[i]];
        const value = parts[order[i]] || 0;

        if ($elem) {
          $elem.text(value);
        }
      }
    }

    function timerCallback() {
      const now = Date.now();
      const ts = info.dt.getTime();

      if (now > ts) {
        const parts = formatInterval(0,info.settings);
        updateFromParts(parts);
        window.clearInterval(info.intervalId);
        return;
      }

      const parts = formatInterval(ts - now,info.settings);
      updateFromParts(parts);
    }

    timerCallback();

    return timerCallback;
  }

  function prepareCountdownWidget(index) {
    const $elem = $(this);
    const dateString = $elem.attr("data-countdown-widget");
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
    settings = Object.assign({},DEFAULT_WIDGET_SETTINGS,settings);

    dt.setTime(ts);

    const info = {
      $elem,
      dt,
      settings
    };

    const callback = makeTimerWidgetCallback(info);
    info.intervalId = window.setInterval(callback,1000);
  }

  $("[data-countdown-inline]").each(prepareCountdownInline);
  $("[data-countdown-widget]").each(prepareCountdownWidget);
});
