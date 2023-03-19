import DevelopmentWarningWidget from "./components/DevelopmentWarningWidget";
import app from "flarum/admin/app";
import DashboardPage from "flarum/admin/components/DashboardPage";
import ExtensionPage from "flarum/admin/components/ExtensionPage";
import Button from "flarum/common/components/Button";
import Link from "flarum/common/components/Link";
import { extend } from "flarum/common/extend";
import extractText from "flarum/common/utils/extractText";

const trans = (key: string, params = {}) => {
  return app.translator.trans(
    `nearata-cloudflare.admin.settings.${key}`,
    params
  );
};

app.initializers.add("nearata-cloudflare", () => {
  extend(DashboardPage.prototype, "availableWidgets", function (items) {
    const developmentMode =
      app.data.settings["nearata-cloudflare.development-mode"];

    if (developmentMode === "1") {
      items.add(
        "nearataCloudflareDevelopment",
        <DevelopmentWarningWidget />,
        100
      );
    }
  });

  app.extensionData
    .for("nearata-cloudflare")
    .registerSetting({
      setting: "nearata-cloudflare.api-key",
      type: "password",
      label: trans("api_key"),
      help: trans("api_key_help", {
        url: (
          <Link
            external={true}
            href="https://developers.cloudflare.com/api/tokens/create"
          />
        ),
      }),
    })
    .registerSetting({
      setting: "nearata-cloudflare.security-level",
      type: "select",
      label: trans("security_level_label"),
      options: {
        off: trans("security_level_options.off"),
        essentially_off: trans("security_level_options.essentially_off"),
        low: trans("security_level_options.low"),
        medium: trans("security_level_options.medium"),
        high: trans("security_level_options.high"),
        under_attack: trans("security_level_options.under_attack"),
      },
      help: trans("refer_to", {
        url: (
          <Link
            external={true}
            href="https://support.cloudflare.com/hc/en-us/articles/200170056"
          />
        ),
      }),
    })
    .registerSetting(() => {
      return (
        <div class="Form-group">
          <h2>{trans("minify_setting.section_title")}</h2>
          <div class="helpText">
            {trans("refer_to", {
              url: (
                <Link
                  external={true}
                  href="https://support.cloudflare.com/hc/en-us/articles/200168196"
                />
              ),
            })}
          </div>
        </div>
      );
    })
    .registerSetting({
      setting: "nearata-cloudflare.minify-css",
      type: "checkbox",
      label: trans("minify_setting.css"),
      help: trans("minify_setting.css_help"),
    })
    .registerSetting({
      setting: "nearata-cloudflare.minify-html",
      type: "checkbox",
      label: trans("minify_setting.html"),
      help: trans("minify_setting.html_help"),
    })
    .registerSetting({
      setting: "nearata-cloudflare.minify-js",
      type: "checkbox",
      label: trans("minify_setting.js"),
      help: trans("minify_setting.js_help"),
    })
    .registerSetting({
      setting: "nearata-cloudflare.development-mode",
      type: "checkbox",
      label: "Development Mode",
      help: trans("refer_to", {
        url: (
          <Link
            external={true}
            href="https://api.cloudflare.com/#zone-settings-change-development-mode-setting"
          />
        ),
      }),
    })
    .registerSetting(function (this: ExtensionPage) {
      return (
        <div class="Form-group">
          {Button.component(
            {
              className: "Button Button--danger",
              loading: this.loading,
              icon: this.success && "fas fa-check",
              onclick: () => {
                if (!confirm(extractText(trans("confirm_text")))) {
                  return;
                }

                this.loading = true;
                this.success = false;

                const url = `${app.forum.attribute(
                  "apiUrl"
                )}/nearata/cloudflare/refreshZone`;

                app
                  .request({
                    url,
                    method: "PATCH",
                  })
                  .then(() => {
                    this.success = true;
                  })
                  .catch(() => {
                    this.success = false;
                  })
                  .finally(() => {
                    this.loading = false;

                    m.redraw();
                  });
              },
            },
            trans("refresh_zone_button_label")
          )}
          <div class="helpText">{trans("refresh_zone_help")}</div>
        </div>
      );
    });
});
