<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-3xl font-bold text-gray-900">Calendar & Scheduling</h2>
        <p class="text-gray-600 mt-1">Manage your appointments via Calendly</p>
      </div>
      <div class="flex gap-3">
        <button 
          @click="openCalendlySettings"
          class="px-5 py-3 bg-white rounded-2xl border-2 border-emerald-200 hover:border-emerald-500 hover:bg-emerald-50 transition-all font-medium text-gray-700"
        >
          <Settings class="w-5 h-5 inline mr-2" />
          Calendly Settings
        </button>
        <button 
          @click="shareSchedulingLink"
          class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl hover:shadow-lg transition-all flex items-center gap-2"
        >
          <Share2 class="w-5 h-5" />
          Share Booking Link
        </button>
      </div>
    </div>

    <!-- Calendly Connection Status -->
    <div v-if="!calendlyConnected" class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-3xl shadow-lg border-2 border-amber-200 p-6">
      <div class="flex items-start gap-4">
        <AlertCircle class="w-6 h-6 text-amber-600 flex-shrink-0 mt-1" />
        <div class="flex-1">
          <h3 class="text-lg font-bold text-gray-900 mb-2">Connect Your Calendly Account</h3>
          <p class="text-gray-700 mb-4">Link your Calendly account to manage appointments, availability, and client bookings seamlessly.</p>
          <button 
            @click="connectCalendly"
            class="px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white rounded-2xl hover:shadow-lg transition-all font-medium"
          >
            Connect Calendly
          </button>
        </div>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-4 gap-4">
      <div class="bg-white rounded-2xl shadow-lg border border-emerald-100 p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 mb-1">Today's Sessions</p>
            <p class="text-3xl font-bold text-gray-900">{{ todaysSessionCount }}</p>
          </div>
          <Calendar class="w-10 h-10 text-emerald-600" />
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-lg border border-teal-100 p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 mb-1">This Week</p>
            <p class="text-3xl font-bold text-gray-900">{{ weekSessionCount }}</p>
          </div>
          <Clock class="w-10 h-10 text-teal-600" />
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 mb-1">Pending</p>
            <p class="text-3xl font-bold text-gray-900">{{ pendingCount }}</p>
          </div>
          <AlertCircle class="w-10 h-10 text-blue-600" />
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-lg border border-purple-100 p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600 mb-1">Available Slots</p>
            <p class="text-3xl font-bold text-gray-900">{{ availableSlots }}</p>
          </div>
          <User class="w-10 h-10 text-purple-600" />
        </div>
      </div>
    </div>

    <!-- Calendar View Tabs -->
    <div class="flex gap-4">
      <button
        v-for="view in calendarViews"
        :key="view.id"
        @click="activeView = view.id"
        :class="[
          'px-6 py-3 rounded-2xl font-medium transition-all',
          activeView === view.id
            ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg'
            : 'bg-white text-gray-700 border border-emerald-100 hover:border-emerald-400'
        ]"
      >
        {{ view.label }}
      </button>
    </div>

    <!-- Upcoming Sessions List -->
    <div class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold text-gray-900">Upcoming Sessions</h3>
        <div class="flex gap-3">
          <button class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl hover:bg-emerald-100 transition-colors flex items-center gap-2">
            <Filter class="w-4 h-4" />
            Filter
          </button>
          <button class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl hover:bg-emerald-100 transition-colors flex items-center gap-2">
            <Download class="w-4 h-4" />
            Export
          </button>
        </div>
      </div>

      <div class="space-y-3">
        <div
          v-for="session in filteredSessions"
          :key="session.id"
          class="p-5 rounded-2xl border-2 border-emerald-100 hover:border-emerald-400 hover:shadow-md transition-all bg-gradient-to-r from-emerald-50/30 to-teal-50/30"
        >
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4 flex-1">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold">
                {{ session.clientInitials }}
              </div>
              <div class="flex-1">
                <div class="flex items-center gap-3">
                  <h4 class="font-bold text-gray-900">{{ session.clientName }}</h4>
                  <span :class="[
                    'px-3 py-1 rounded-full text-xs font-semibold',
                    session.type === 'individual' ? 'bg-emerald-100 text-emerald-700' :
                    session.type === 'group' ? 'bg-teal-100 text-teal-700' :
                    session.type === 'family' ? 'bg-blue-100 text-blue-700' :
                    'bg-purple-100 text-purple-700'
                  ]">
                    {{ session.type }}
                  </span>
                  <span v-if="session.isFirstSession" class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                    First Session
                  </span>
                </div>
                <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                  <span class="flex items-center gap-1">
                    <Calendar class="w-4 h-4" />
                    {{ session.date }}
                  </span>
                  <span class="flex items-center gap-1">
                    <Clock class="w-4 h-4" />
                    {{ session.time }} ({{ session.duration }} min)
                  </span>
                  <span class="flex items-center gap-1">
                    <Video class="w-4 h-4" />
                    {{ session.platform }}
                  </span>
                </div>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button 
                @click="copyMeetingLink(session.meetingLink)"
                class="px-4 py-2 bg-white rounded-xl hover:bg-emerald-50 transition-colors border border-emerald-200 flex items-center gap-2 text-sm font-medium"
                title="Copy meeting link"
              >
                <Link class="w-4 h-4" />
                Copy Link
              </button>
              <button 
                @click="viewSessionDetails(session.id)"
                class="px-4 py-2 bg-white rounded-xl hover:bg-emerald-50 transition-colors border border-emerald-200 flex items-center gap-2 text-sm font-medium"
              >
                <Eye class="w-4 h-4" />
                View
              </button>
              <button 
                @click="openReschedule(session.id)"
                class="px-4 py-2 bg-white rounded-xl hover:bg-emerald-50 transition-colors border border-emerald-200 flex items-center gap-2 text-sm font-medium"
              >
                <Edit class="w-4 h-4" />
                Reschedule
              </button>
              <button class="p-2 hover:bg-emerald-50 rounded-xl transition-colors">
                <MoreVertical class="w-5 h-5 text-gray-600" />
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="filteredSessions.length === 0" class="text-center py-12">
        <Calendar class="w-16 h-16 text-gray-300 mx-auto mb-4" />
        <p class="text-gray-600 font-medium">No upcoming sessions</p>
        <p class="text-gray-500 text-sm mt-1">Your schedule is clear for now</p>
      </div>
    </div>

    <!-- Calendly Quick Actions -->
    <div v-if="calendlyConnected" class="bg-white rounded-3xl shadow-lg border border-emerald-100 p-6">
      <h3 class="text-2xl font-bold text-gray-900 mb-6">Calendly Quick Actions</h3>
      <div class="grid grid-cols-3 gap-4">
        <button 
          @click="openCalendlyDashboard"
          class="p-6 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl border-2 border-emerald-100 hover:border-emerald-400 hover:shadow-md transition-all text-left"
        >
          <Calendar class="w-8 h-8 text-emerald-600 mb-3" />
          <h4 class="font-bold text-gray-900 mb-1">View Full Calendar</h4>
          <p class="text-sm text-gray-600">Open your Calendly dashboard</p>
        </button>
        <button 
          @click="openAvailability"
          class="p-6 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl border-2 border-emerald-100 hover:border-emerald-400 hover:shadow-md transition-all text-left"
        >
          <Clock class="w-8 h-8 text-teal-600 mb-3" />
          <h4 class="font-bold text-gray-900 mb-1">Set Availability</h4>
          <p class="text-sm text-gray-600">Manage your schedule hours</p>
        </button>
        <button 
          @click="openEventTypes"
          class="p-6 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl border-2 border-emerald-100 hover:border-emerald-400 hover:shadow-md transition-all text-left"
        >
          <Settings class="w-8 h-8 text-blue-600 mb-3" />
          <h4 class="font-bold text-gray-900 mb-1">Event Types</h4>
          <p class="text-sm text-gray-600">Configure session types</p>
        </button>
      </div>
      <div class="mt-6 p-4 bg-emerald-50 rounded-xl border border-emerald-200">
        <div class="flex items-start gap-3">
          <Link class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" />
          <div class="flex-1">
            <p class="font-semibold text-gray-900 mb-1">Your Booking Page</p>
            <div class="flex items-center gap-2">
              <input 
                :value="calendlyUrl" 
                readonly
                class="flex-1 px-3 py-2 bg-white rounded-lg border border-emerald-200 text-sm text-gray-700"
              />
              <button 
                @click="shareSchedulingLink"
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium"
              >
                Copy Link
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Session Types Legend -->
    <div class="grid grid-cols-4 gap-4">
      <div class="bg-white rounded-2xl shadow-lg border border-emerald-100 p-4">
        <div class="flex items-center gap-3">
          <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
          <span class="text-sm font-medium text-gray-700">Individual Therapy</span>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-lg border border-teal-100 p-4">
        <div class="flex items-center gap-3">
          <div class="w-3 h-3 rounded-full bg-teal-500"></div>
          <span class="text-sm font-medium text-gray-700">Group Therapy</span>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-4">
        <div class="flex items-center gap-3">
          <div class="w-3 h-3 rounded-full bg-blue-500"></div>
          <span class="text-sm font-medium text-gray-700">Family Therapy</span>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-lg border border-purple-100 p-4">
        <div class="flex items-center gap-3">
          <div class="w-3 h-3 rounded-full bg-purple-500"></div>
          <span class="text-sm font-medium text-gray-700">Couples Therapy</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { 
  Calendar, Clock, Settings, Share2, AlertCircle, User, Filter, Download,
  Video, Link, Eye, Edit, MoreVertical, X
} from 'lucide-vue-next';

// State
const calendlyConnected = ref(false);
const activeView = ref('upcoming');
const calendlyUrl = ref('');
const calendlyUsername = ref('');
const todaysSessionCount = ref(0);
const weekSessionCount = ref(0);
const pendingCount = ref(0);
const availableSlots = ref(0);
const upcomingSessions = ref<any[]>([]);

// Calendar view options
const calendarViews = [
  { id: 'upcoming', label: 'Upcoming' },
  { id: 'today', label: 'Today' },
  { id: 'week', label: 'This Week' },
  { id: 'month', label: 'This Month' }
];

// Computed property to filter sessions based on active view
const filteredSessions = computed(() => {
  const now = new Date();
  const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
  const weekStart = new Date(now.setDate(now.getDate() - now.getDay()));
  const weekEnd = new Date(now.setDate(now.getDate() + 6));
  const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
  const monthEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0);

  return upcomingSessions.value.filter(session => {
    const sessionDate = new Date(session.startTime);
    switch (activeView.value) {
      case 'today':
        return sessionDate.toDateString() === today.toDateString();
      case 'week':
        return sessionDate >= weekStart && sessionDate <= weekEnd;
      case 'month':
        return sessionDate >= monthStart && sessionDate <= monthEnd;
      case 'upcoming':
      default:
        return sessionDate >= now;
    }
  });
});

// Calendly API configuration
const CALENDLY_API_BASE = 'https://api.calendly.com';
const CLIENT_ID = import.meta.env.VITE_CALENDLY_CLIENT_ID;
const CLIENT_SECRET = import.meta.env.VITE_CALENDLY_CLIENT_SECRET;
const REDIRECT_URI = import.meta.env.VITE_CALENDLY_REDIRECT_URI || 'http://localhost:5173/therapist/callback';

// Reactive reference for access token
const accessToken = ref(localStorage.getItem('calendly_access_token') || '');
const router = useRouter();

// Helper to get OAuth token
const getOAuthToken = async (code: string) => {
  try {
    const response = await axios.post('https://auth.calendly.com/oauth/token', {
      grant_type: 'authorization_code',
      client_id: CLIENT_ID,
      client_secret: CLIENT_SECRET,
      code,
      redirect_uri: REDIRECT_URI,
    });
    accessToken.value = response.data.access_token;
    localStorage.setItem('calendly_access_token', accessToken.value);
    localStorage.setItem('calendly_refresh_token', response.data.refresh_token);
    return response.data.access_token;
  } catch (error: any) {
    console.error('Error fetching OAuth token:', {
      message: error.message,
      response: error.response?.data,
      status: error.response?.status,
    });
    calendlyConnected.value = false;
    throw error;
  }
};

// Refresh OAuth token
const refreshOAuthToken = async () => {
  try {
    const refreshToken = localStorage.getItem('calendly_refresh_token');
    if (!refreshToken) throw new Error('No refresh token available');
    const response = await axios.post('https://auth.calendly.com/oauth/token', {
      grant_type: 'refresh_token',
      client_id: CLIENT_ID,
      client_secret: CLIENT_SECRET,
      refresh_token: refreshToken,
    });
    accessToken.value = response.data.access_token;
    localStorage.setItem('calendly_access_token', accessToken.value);
    localStorage.setItem('calendly_refresh_token', response.data.refresh_token);
    return response.data.access_token;
  } catch (error: any) {
    console.error('Error refreshing OAuth token:', {
      message: error.message,
      response: error.response?.data,
      status: error.response?.status,
    });
    calendlyConnected.value = false;
    throw error;
  }
};

// Fetch Calendly user data (to get user URI)
const fetchUserData = async () => {
  try {
    const response = await axios.get(`${CALENDLY_API_BASE}/users/me`, {
      headers: { Authorization: `Bearer ${accessToken.value}` },
    });
    const user = response.data.resource;
    calendlyUrl.value = user.scheduling_url;
    calendlyUsername.value = user.slug;
    calendlyConnected.value = true;
    return user.uri; // Return user URI for events
  } catch (error: any) {
    console.error('Error fetching user data:', {
      message: error.message,
      response: error.response?.data,
      status: error.response?.status,
    });
    calendlyConnected.value = false;
    return null;
  }
};

// Fetch ALL scheduled events (no filters, with pagination)
const fetchAllScheduledEvents = async () => {
  let allEvents: any[] = [];
  let nextPageToken: string | null = null;

  try {
    // Get user URI first
    const userUri = await fetchUserData();
    if (!userUri) {
      throw new Error('Failed to fetch user URI');
    }

    do {
      const params: any = {
        user: userUri,
        count: 100, // Max per page per documentation
        sort: 'start_time:asc',
      };

      if (nextPageToken) {
        params.page_token = nextPageToken;
      }

      console.log('Fetching events with params:', params); // Debug log

      const response = await axios.get(`${CALENDLY_API_BASE}/scheduled_events`, {
        headers: { Authorization: `Bearer ${accessToken.value}` },
        params,
      });

      const { collection, pagination } = response.data;
      allEvents = allEvents.concat(collection);
      nextPageToken = pagination.next_page_token || null;

      console.log(`Fetched ${collection.length} events, total so far: ${allEvents.length}, next_page_token: ${nextPageToken}`); // Debug log
    } while (nextPageToken);

    // Map to session format
    upcomingSessions.value = allEvents.map((event: any) => ({
      id: event.uri.split('/').pop(),
      clientName: event.name || 'Unnamed Event',
      clientInitials: event.name ? event.name.split(' ').map((n: string) => n[0]).join('').slice(0, 2) : 'NA',
      type: event.event_type ? event.event_type.split('/').pop()?.toLowerCase() : 'individual',
      date: new Date(event.start_time).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'short',
        day: 'numeric',
      }),
      startTime: event.start_time, // For filtering
      time: new Date(event.start_time).toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        timeZoneName: 'short',
      }),
      duration: Math.round((new Date(event.end_time).getTime() - new Date(event.start_time).getTime()) / 60000),
      platform: event.location?.type === 'integrations:google_video_conferencing' ? 'Google Meet' :
                event.location?.type === 'integrations:zoom_video_conferencing' ? 'Zoom' :
                event.location?.type || 'In-person',
      meetingLink: event.location?.join_url || '#',
      status: event.status || 'active', // e.g., 'active', 'canceled'
      isFirstSession: false, // Can be enhanced with client history
    }));

    console.log(`Total events fetched: ${allEvents.length}`); // Debug log

    // Update stats
    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const weekStart = new Date(now);
    weekStart.setDate(weekStart.getDate() - weekStart.getDay());
    const weekEnd = new Date(weekStart);
    weekEnd.setDate(weekEnd.getDate() + 6);

    todaysSessionCount.value = upcomingSessions.value.filter(s => new Date(s.startTime).toDateString() === today.toDateString()).length;
    weekSessionCount.value = upcomingSessions.value.filter(s => {
      const sessionDate = new Date(s.startTime);
      return sessionDate >= weekStart && sessionDate <= weekEnd;
    }).length;
    pendingCount.value = upcomingSessions.value.filter(s => s.status === 'pending' || !s.status).length;
    availableSlots.value = 24; // Placeholder; compute from event types if needed

  } catch (error: any) {
    console.error('Error fetching scheduled events:', {
      status: error.response?.status,
      data: error.response?.data,
      message: error.message,
      url: error.config?.url,
      params: error.config?.params,
    });
    upcomingSessions.value = [];
    if (error.response?.status === 404) {
      console.error('404 on /scheduled_events. Possible causes:');
      console.error('- Invalid user URI (ensure /users/me returns a valid URI)');
      console.error('- Missing calendly.events.read scope (check OAuth app settings)');
      console.error('- No events exist for this user (create a test event in Calendly)');
      console.error('- Rate limit exceeded (200 requests/hour for OAuth apps)');
    } else if (error.response?.status === 401) {
      console.error('401 Unauthorized: Invalid or expired token. Try refreshing token or re-authenticating.');
    } else if (error.response?.status === 403) {
      console.error('403 Forbidden: Token lacks necessary permissions. Ensure calendly.events.read scope.');
    }
  }
};

// Check for OAuth callback code in URL
const handleOAuthCallback = async () => {
  const urlParams = new URLSearchParams(window.location.search);
  const code = urlParams.get('code');
  if (code) {
    try {
      await getOAuthToken(code);
      await fetchAllScheduledEvents();
      // Clear URL params and redirect to therapist calendar
      router.push('/therapist/calendar');
    } catch (error) {
      console.error('Error handling OAuth callback:', error);
      calendlyConnected.value = false;
      router.push('/therapist/calendar'); // Redirect even on error
    }
  }
};

// Initialize component
onMounted(async () => {
  await handleOAuthCallback();
  if (accessToken.value) {
    try {
      await fetchAllScheduledEvents();
    } catch (error) {
      try {
        await refreshOAuthToken();
        await fetchAllScheduledEvents();
      } catch (refreshError) {
        console.error('Failed to refresh token:', refreshError);
        calendlyConnected.value = false;
      }
    }
  } else {
    calendlyConnected.value = false;
  }
});

// Methods
const connectCalendly = () => {
  const authUrl = `https://auth.calendly.com/oauth/authorize?client_id=${CLIENT_ID}&redirect_uri=${encodeURIComponent(REDIRECT_URI)}&response_type=code&scope=calendly.events.read`;
  console.log('Redirecting to:', authUrl); // Debug log
  window.location.href = authUrl;
};

const openCalendlySettings = () => {
  window.open('https://calendly.com/event_types/user/me', '_blank');
};

const shareSchedulingLink = () => {
  if (calendlyUrl.value) {
    navigator.clipboard.writeText(calendlyUrl.value);
    alert('Scheduling link copied to clipboard!');
  } else {
    alert('No scheduling link available. Please connect your Calendly account.');
  }
};

const openCalendlyDashboard = () => {
  window.open('https://calendly.com/app/scheduled_events/user/me', '_blank');
};

const openAvailability = () => {
  window.open('https://calendly.com/app/availability', '_blank');
};

const openEventTypes = () => {
  window.open('https://calendly.com/app/event_types/user/me', '_blank');
};

const copyMeetingLink = (link: string) => {
  navigator.clipboard.writeText(link);
  alert('Meeting link copied to clipboard!');
};

const viewSessionDetails = (sessionId: number) => {
  console.log('View session:', sessionId);
  // Navigate to session details page
};

const openReschedule = (sessionId: number) => {
  console.log('Reschedule session:', sessionId);
  window.open('https://calendly.com/app/scheduled_events/user/me', '_blank');
};
</script>