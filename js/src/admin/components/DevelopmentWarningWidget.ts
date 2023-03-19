import app from "flarum/admin/app";
import DashboardWidget from "flarum/admin/components/DashboardWidget";
import Alert from "flarum/common/components/Alert";

export default class DevelopmentWarningWidget extends DashboardWidget {
  className() {
    return "NearataCloudflare DevelopmentWarningWidget";
  }

  content() {
    return [
      m(Alert, {
        type: "warning",
        dismissible: false,
        title: app.translator.trans(
          "nearata-cloudflare.admin.development_mode_widget.title"
        ),
        icon: "fas fa-exclamation-triangle",
      }),
    ];
  }
}
