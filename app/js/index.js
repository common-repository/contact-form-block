const { render } = wp.element;
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

const queryClient = new QueryClient({ 
	defaultOptions: { 
		queries: { 
			refetchOnWindowFocus: false,
			refetchOnMount: false,
			retry: false,
			placeholderData: (prev) => prev,
		}
	}
});

// Neko UI
import { NekoUI } from '@neko-ui';
import { Dashboard } from '@common';

// Components
import Settings from '@app/components/Settings';

document.addEventListener('DOMContentLoaded', function(event) {

	// Settings
	const settings = document.getElementById('mcfb-admin-settings');
	if (settings) {
		render((<NekoUI><Settings /></NekoUI>), settings);
	}

	// Common
	const meowDashboard = document.getElementById('meow-common-dashboard');
	if (meowDashboard) {
		render(<QueryClientProvider client={queryClient}>
			<NekoUI><Dashboard /></NekoUI>
		</QueryClientProvider>, meowDashboard);
	}

});
