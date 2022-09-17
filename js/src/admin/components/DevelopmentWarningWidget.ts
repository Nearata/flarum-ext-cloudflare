import DashboardWidget from "flarum/admin/components/DashboardWidget";
import Alert from "flarum/common/components/Alert";

export default class DevelopmentWarningWidget extends DashboardWidget {
    className() {
        return "DevelopmentWarningWidget";
    }

    content() {
        return [
            m(Alert, {
                type: "warning",
                dismissible: false,
                title: "Cloudflare Development Mode active",
                icon: "fas fa-exclamation-triangle",
            }),
        ];
    }
}
