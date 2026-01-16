<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // #region agent log
        file_put_contents('c:\laragon\www\ro-water-ledger\.cursor\debug.log', json_encode([
            'id' => 'log_' . time() . '_' . uniqid(),
            'timestamp' => time() * 1000,
            'location' => 'AuthenticatedSessionController.php:27',
            'message' => 'Starting login store method',
            'data' => ['csrf_token' => $request->input('_token'), 'has_session' => $request->hasSession()],
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'A'
        ]) . "\n", FILE_APPEND);
        // #endregion

        try {
            $request->authenticate();
            // #region agent log
            file_put_contents('c:\laragon\www\ro-water-ledger\.cursor\debug.log', json_encode([
                'id' => 'log_' . time() . '_' . uniqid(),
                'timestamp' => time() * 1000,
                'location' => 'AuthenticatedSessionController.php:30',
                'message' => 'Authentication successful',
                'data' => ['user_id' => Auth::id()],
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'C'
            ]) . "\n", FILE_APPEND);
            // #endregion
        } catch (\Exception $e) {
            // #region agent log
            file_put_contents('c:\laragon\www\ro-water-ledger\.cursor\debug.log', json_encode([
                'id' => 'log_' . time() . '_' . uniqid(),
                'timestamp' => time() * 1000,
                'location' => 'AuthenticatedSessionController.php:35',
                'message' => 'Authentication failed',
                'data' => ['error' => $e->getMessage()],
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'B'
            ]) . "\n", FILE_APPEND);
            // #endregion
            throw $e;
        }

        try {
            $request->session()->regenerate();
            // #region agent log
            file_put_contents('c:\laragon\www\ro-water-ledger\.cursor\debug.log', json_encode([
                'id' => 'log_' . time() . '_' . uniqid(),
                'timestamp' => time() * 1000,
                'location' => 'AuthenticatedSessionController.php:40',
                'message' => 'Session regenerated',
                'data' => ['session_id' => session()->getId()],
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'E'
            ]) . "\n", FILE_APPEND);
            // #endregion
        } catch (\Exception $e) {
            // #region agent log
            file_put_contents('c:\laragon\www\ro-water-ledger\.cursor\debug.log', json_encode([
                'id' => 'log_' . time() . '_' . uniqid(),
                'timestamp' => time() * 1000,
                'location' => 'AuthenticatedSessionController.php:45',
                'message' => 'Session regeneration failed',
                'data' => ['error' => $e->getMessage()],
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'E'
            ]) . "\n", FILE_APPEND);
            // #endregion
            throw $e;
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
