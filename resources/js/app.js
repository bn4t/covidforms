import * as Sentry from "@sentry/browser";
import { Integrations } from "@sentry/tracing";

Sentry.init({
    dsn: "https://007ef49fd9424f86be4cd0828547e446@o987414.ingest.sentry.io/5944287",
    integrations: [new Integrations.BrowserTracing()],

    // Set tracesSampleRate to 1.0 to capture 100%
    // of transactions for performance monitoring.
    // We recommend adjusting this value in production
    tracesSampleRate: 1.0,
});

require('./bootstrap');

import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()

require('./sorttable')
