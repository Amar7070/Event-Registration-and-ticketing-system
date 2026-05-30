import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Home from './pages/Home';
import EventListing from './pages/EventListing';
import EventDetail from './pages/EventDetail';
import Login from './pages/Login';
import Register from './pages/Register';
import ForgotPassword from './pages/ForgotPassword';
import ResetPassword from './pages/ResetPassword';
import AuthCallback from './pages/AuthCallback';
import Dashboard from './pages/Dashboard';
import Checkout from './pages/Checkout';
import PaymentCheckout from './pages/PaymentCheckout';
import MyTickets from './pages/MyTickets';
import TicketDetail from './pages/TicketDetail';
import MyWaitlists from './pages/MyWaitlists';
import Profile from './pages/Profile';

import OrganizerEvents from './pages/organizer/Events';
import OrganizerCreateEvent from './pages/organizer/CreateEvent';
import OrganizerScanner from './pages/organizer/Scanner';
import OrganizerAttendees from './pages/organizer/Attendees';
import OrganizerAnalytics from './pages/organizer/Analytics';
import OrganizerPromote from './pages/organizer/Promote';
import OrganizerCoupons from './pages/organizer/Coupons';
import OrganizerGlobalAttendees from './pages/organizer/GlobalAttendees';
import OrganizerReviews from './pages/organizer/Reviews';
import OrganizerCopyright from './pages/organizer/Copyright';
// Admin Pages
import AdminDashboard from './pages/admin/Dashboard';
import AdminUsers from './pages/admin/Users';
import AdminCategories from './pages/admin/Categories';
import AdminCoupons from './pages/admin/Coupons';
import AdminPromotions from './pages/admin/Promotions';
import AdminEvents from './pages/admin/Events';
import AdminOrganizers from './pages/admin/Organizers';
import AdminRevenue from './pages/admin/Revenue';
import AdminClaims from './pages/admin/CopyrightClaims';
import AdminReviews from './pages/admin/Reviews';
import AdminContacts from './pages/admin/Contacts';

// Static Pages
import About from './pages/About';
import Contact from './pages/Contact';
import Blog from './pages/Blog';
import Help from './pages/Help';
import Pricing from './pages/Pricing';

// Protected Route wrapper component
const ProtectedRoute = ({ children }) => {
  const token = localStorage.getItem('token') || localStorage.getItem('api_token');
  if (!token) {
    return <Navigate to="/login" replace />;
  }
  return children;
};

// Role Protected Route wrapper component
const RoleProtectedRoute = ({ children, allowedRoles }) => {
  const token = localStorage.getItem('token') || localStorage.getItem('api_token');
  if (!token) {
    return <Navigate to="/login" replace />;
  }
  
  let role = localStorage.getItem('user_role');
  if (!role || role === 'undefined' || role === 'null') {
    role = 'user';
  }
  const isApproved = localStorage.getItem('is_approved') === '1' || localStorage.getItem('is_approved') === 'true' || localStorage.getItem('is_approved') === true;
  
  if (allowedRoles && !allowedRoles.includes(role)) {
    if (role === 'admin') return <Navigate to="/admin/dashboard" replace />;
    if (role === 'organizer') return <Navigate to="/organizer/analytics" replace />;
    return <Navigate to="/dashboard" replace />;
  }

  // Block organizers who haven't been approved yet by Governance
  if (role === 'organizer' && !isApproved) {
    return <Navigate to="/profile" replace />;
  }
  
  return children;
};

function App() {
  return (
    <Router>
      <Routes>
        {/* Public Routes */}
        <Route path="/" element={<Home />} />
        <Route path="/about" element={<About />} />
        <Route path="/contact" element={<Contact />} />
        <Route path="/events" element={<EventListing />} />
        <Route path="/events/:slug" element={<EventDetail />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/forgot-password" element={<ForgotPassword />} />
        <Route path="/reset-password" element={<ResetPassword />} />
        <Route path="/auth/callback" element={<AuthCallback />} />
        <Route path="/contact" element={<Contact />} />
        <Route path="/blog" element={<Blog />} />
        <Route path="/help" element={<Help />} />
        <Route path="/pricing" element={<Pricing />} />

        {/* Protected Attendee Routes */}
        <Route path="/dashboard" element={<RoleProtectedRoute allowedRoles={['attendee', 'user']}><Dashboard /></RoleProtectedRoute>} />
        <Route path="/checkout" element={<RoleProtectedRoute allowedRoles={['attendee', 'user']}><Checkout /></RoleProtectedRoute>} />
        <Route path="/payment-checkout" element={<RoleProtectedRoute allowedRoles={['attendee', 'user']}><PaymentCheckout /></RoleProtectedRoute>} />
        <Route path="/my-tickets" element={<RoleProtectedRoute allowedRoles={['attendee', 'user']}><MyTickets /></RoleProtectedRoute>} />
        <Route path="/my-tickets/:reference" element={<RoleProtectedRoute allowedRoles={['attendee', 'user']}><TicketDetail /></RoleProtectedRoute>} />
        <Route path="/my-waitlists" element={<RoleProtectedRoute allowedRoles={['attendee', 'user']}><MyWaitlists /></RoleProtectedRoute>} />
        <Route path="/profile" element={<ProtectedRoute><Profile /></ProtectedRoute>} />

        {/* Organizer Console Routes */}
        <Route path="/organizer/events" element={<RoleProtectedRoute allowedRoles={['organizer']}><OrganizerEvents /></RoleProtectedRoute>} />
        <Route path="/organizer/events/create" element={<RoleProtectedRoute allowedRoles={['organizer']}><OrganizerCreateEvent /></RoleProtectedRoute>} />
        <Route path="/organizer/events/:id/edit" element={<RoleProtectedRoute allowedRoles={['organizer']}><OrganizerCreateEvent /></RoleProtectedRoute>} />
        <Route path="/organizer/events/:id/scan" element={<RoleProtectedRoute allowedRoles={['organizer']}><OrganizerScanner /></RoleProtectedRoute>} />
        <Route path="/organizer/events/:id/attendees" element={<RoleProtectedRoute allowedRoles={['organizer']}><OrganizerAttendees /></RoleProtectedRoute>} />
        <Route path="/organizer/events/:id/promote" element={<RoleProtectedRoute allowedRoles={['organizer']}><OrganizerPromote /></RoleProtectedRoute>} />
        <Route path="/organizer/analytics" element={<RoleProtectedRoute allowedRoles={['organizer']}><OrganizerAnalytics /></RoleProtectedRoute>} />
        <Route path="/organizer/coupons" element={<RoleProtectedRoute allowedRoles={['organizer']}><OrganizerCoupons /></RoleProtectedRoute>} />
        <Route path="/organizer/attendees" element={<RoleProtectedRoute allowedRoles={['organizer']}><OrganizerGlobalAttendees /></RoleProtectedRoute>} />
        <Route path="/organizer/reviews" element={<RoleProtectedRoute allowedRoles={['organizer']}><OrganizerReviews /></RoleProtectedRoute>} />
        <Route path="/organizer/copyright" element={<RoleProtectedRoute allowedRoles={['organizer']}><OrganizerCopyright /></RoleProtectedRoute>} />
        {/* Admin Control Room Routes */}
        <Route path="/admin/dashboard" element={<RoleProtectedRoute allowedRoles={['admin']}><AdminDashboard /></RoleProtectedRoute>} />
        <Route path="/admin/users" element={<RoleProtectedRoute allowedRoles={['admin']}><AdminUsers /></RoleProtectedRoute>} />
        <Route path="/admin/categories" element={<RoleProtectedRoute allowedRoles={['admin']}><AdminCategories /></RoleProtectedRoute>} />
        <Route path="/admin/coupons" element={<RoleProtectedRoute allowedRoles={['admin']}><AdminCoupons /></RoleProtectedRoute>} />
        <Route path="/admin/promotions" element={<RoleProtectedRoute allowedRoles={['admin']}><AdminPromotions /></RoleProtectedRoute>} />
        <Route path="/admin/events" element={<RoleProtectedRoute allowedRoles={['admin']}><AdminEvents /></RoleProtectedRoute>} />
        <Route path="/admin/organizers/pending" element={<RoleProtectedRoute allowedRoles={['admin']}><AdminOrganizers /></RoleProtectedRoute>} />
        <Route path="/admin/revenue" element={<RoleProtectedRoute allowedRoles={['admin']}><AdminRevenue /></RoleProtectedRoute>} />
        <Route path="/admin/claims" element={<RoleProtectedRoute allowedRoles={['admin']}><AdminClaims /></RoleProtectedRoute>} />
        <Route path="/admin/reviews" element={<RoleProtectedRoute allowedRoles={['admin']}><AdminReviews /></RoleProtectedRoute>} />
        <Route path="/admin/contacts" element={<RoleProtectedRoute allowedRoles={['admin']}><AdminContacts /></RoleProtectedRoute>} />

        {/* Catch-all Redirect */}
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </Router>
  );
}

export default App;
