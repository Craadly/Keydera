<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keydera Installation Wizard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #5b21b6;
            --primary-dark: #4c1d95;
            --primary-light: #7c3aed;
            --secondary: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #111827;
            --gray: #6b7280;
            --light: #f9fafb;
            --white: #ffffff;
            --border: #e5e7eb;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #0f0f23;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated Gradient Orbs Background */
        .background-animation {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            background: linear-gradient(125deg, #0f0f23 0%, #1a1a3e 100%);
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.4;
            animation: float 20s infinite ease-in-out;
        }

        .orb1 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, #7c3aed 0%, transparent 70%);
            top: -200px;
            left: -200px;
            animation-duration: 25s;
        }

        .orb2 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #06b6d4 0%, transparent 70%);
            bottom: -150px;
            right: -150px;
            animation-duration: 20s;
            animation-delay: 5s;
        }

        .orb3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #ec4899 0%, transparent 70%);
            top: 50%;
            left: 30%;
            animation-duration: 30s;
            animation-delay: 10s;
        }

        .orb4 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, #10b981 0%, transparent 70%);
            bottom: 20%;
            right: 25%;
            animation-duration: 22s;
            animation-delay: 15s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            25% {
                transform: translate(50px, -50px) scale(1.1);
            }
            50% {
                transform: translate(-30px, 30px) scale(0.9);
            }
            75% {
                transform: translate(30px, 50px) scale(1.05);
            }
        }

        /* Grid Pattern Overlay */
        .grid-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.01) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.01) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 1;
        }

        /* Animated Particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: particle-float 15s infinite linear;
        }

        @keyframes particle-float {
            from {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            to {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }

        .particle:nth-child(1) { left: 10%; animation-delay: 0s; animation-duration: 13s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 2s; animation-duration: 16s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 4s; animation-duration: 12s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 6s; animation-duration: 18s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 8s; animation-duration: 14s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 10s; animation-duration: 17s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 12s; animation-duration: 15s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 14s; animation-duration: 13s; }
        .particle:nth-child(9) { left: 90%; animation-delay: 16s; animation-duration: 19s; }
        .particle:nth-child(10) { left: 95%; animation-delay: 18s; animation-duration: 11s; }

        .container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 620px;
        }

        .installation-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            box-shadow: 
                0 20px 25px -5px rgba(0, 0, 0, 0.2),
                0 10px 10px -5px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                0 0 80px rgba(124, 58, 237, 0.2);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 35px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
			display: flex;
			flex-direction: column;
			align-items: center;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: shimmer 3s infinite;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                var(--secondary) 0%, 
                var(--primary-light) 25%, 
                var(--secondary) 50%, 
                var(--primary-light) 75%, 
                var(--secondary) 100%);
            background-size: 200% auto;
            animation: gradient 3s linear infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        @keyframes gradient {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .logo {
            width: 250px;
            height: auto;
            margin-bottom: 15px;
            filter: brightness(0) invert(1);
        }

        .card-header h1 {
            color: white;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .card-body {
            padding: 30px;
        }

        /* Progress Steps - Complete Redesign */
        .progress-container {
            position: relative;
            margin-bottom: 50px;
            padding: 20px;
            background: linear-gradient(135deg, rgba(91, 33, 182, 0.03) 0%, rgba(124, 58, 237, 0.03) 100%);
            border-radius: 16px;
            border: 1px solid rgba(91, 33, 182, 0.1);
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            z-index: 2;
        }

        /* New connecting line design */
        .progress-line-container {
            position: absolute;
            top: 45px;
            left: 60px;
            right: 60px;
            height: 4px;
            background: rgba(226, 232, 240, 0.5);
            border-radius: 100px;
            overflow: hidden;
        }

        .progress-line-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: repeating-linear-gradient(
                90deg,
                transparent,
                transparent 10px,
                rgba(91, 33, 182, 0.05) 10px,
                rgba(91, 33, 182, 0.05) 20px
            );
            animation: progressMove 20s linear infinite;
        }

        @keyframes progressMove {
            0% { transform: translateX(0); }
            100% { transform: translateX(20px); }
        }

        .progress-line-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
            width: 0%;
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 100px;
            box-shadow: 0 2px 8px rgba(91, 33, 182, 0.3);
        }

        .progress-line-fill::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 30px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6));
            animation: shine 2s ease-in-out infinite;
            border-radius: 100px;
        }

        @keyframes shine {
            0%, 100% { opacity: 0; }
            50% { opacity: 1; }
        }

        /* Redesigned Step Circles */
        .step {
            position: relative;
            z-index: 3;
            text-align: center;
            flex: 1;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .step:hover {
            transform: translateY(-3px);
        }

        .step-wrapper {
            position: relative;
            display: inline-block;
        }

        .step-circle-outer {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(91, 33, 182, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
            opacity: 0;
            transition: all 0.4s ease;
        }

        .step.active .step-circle-outer {
            opacity: 1;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.3; }
            50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.1; }
        }

        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            border: 3px solid var(--border);
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            color: var(--gray);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .step-circle::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary), var(--primary-light), var(--secondary));
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
        }

        .step.active .step-circle::before {
            opacity: 1;
            animation: rotateGradient 3s linear infinite;
        }

        @keyframes rotateGradient {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .step.active .step-circle {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-color: transparent;
            color: white;
            transform: scale(1.15);
            box-shadow: 
                0 4px 20px rgba(91, 33, 182, 0.4),
                0 0 0 8px rgba(91, 33, 182, 0.08);
            font-size: 18px;
        }

        .step.completed .step-circle {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            border-color: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
            box-shadow: 0 2px 10px rgba(16, 185, 129, 0.3);
        }

        .step.completed .step-circle .step-number {
            display: none;
        }

        .step.completed .step-circle::after {
            content: '✓';
            font-size: 22px;
            font-weight: 700;
            animation: checkIn 0.4s ease-out;
        }

        @keyframes checkIn {
            from {
                opacity: 0;
                transform: scale(0) rotate(-180deg);
            }
            to {
                opacity: 1;
                transform: scale(1) rotate(0);
            }
        }

        /* Step Icon */
        .step-icon {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            box-shadow: 0 2px 6px rgba(245, 158, 11, 0.4);
            animation: bounceIn 0.5s ease-out;
        }

        @keyframes bounceIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .step.active .step-icon {
            display: flex;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            box-shadow: 0 2px 6px rgba(91, 33, 182, 0.4);
        }

        .step.completed .step-icon {
            display: flex;
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.4);
        }

        /* Step Labels */
        .step-info {
            position: relative;
        }

        .step-label {
            font-size: 13px;
            color: var(--gray);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            margin-bottom: 4px;
        }

        .step-description {
            font-size: 11px;
            color: rgba(107, 114, 128, 0.7);
            font-weight: 400;
            display: none;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .step.active .step-label {
            color: var(--primary);
            font-weight: 700;
            font-size: 14px;
        }

        .step.active .step-description {
            display: block;
            color: var(--primary-light);
        }

        .step.completed .step-label {
            color: var(--success);
        }

        .step.completed .step-description {
            display: block;
            color: rgba(16, 185, 129, 0.8);
        }

        /* Progress Percentage */
        .progress-percentage {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(91, 33, 182, 0.3);
            opacity: 0;
            transition: opacity 0.3s ease;
            white-space: nowrap;
        }

        .progress-percentage.show {
            opacity: 1;
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from { transform: translateX(-50%) translateY(-10px); }
            to { transform: translateX(-50%) translateY(0); }
        }

        /* Mobile Responsive */
        @media (max-width: 640px) {
            .progress-container {
                padding: 15px;
                margin-bottom: 30px;
            }

            .progress-line-container {
                left: 30px;
                right: 30px;
                top: 30px;
                height: 2px;
            }

            .step-circle {
                width: 40px;
                height: 40px;
                font-size: 14px;
            }

            .step-circle-outer {
                width: 56px;
                height: 56px;
            }

            .step.active .step-circle {
                font-size: 16px;
                box-shadow: 
                    0 2px 10px rgba(91, 33, 182, 0.3),
                    0 0 0 6px rgba(91, 33, 182, 0.08);
            }

            .step-label {
                font-size: 10px;
            }

            .step.active .step-label {
                font-size: 11px;
            }

            .step-description {
                font-size: 9px;
            }

            .step-icon {
                width: 20px;
                height: 20px;
                font-size: 10px;
                top: -6px;
                right: -6px;
            }

            .progress-percentage {
                padding: 3px 8px;
                font-size: 10px;
                top: -25px;
            }
        }

        @media (max-width: 480px) {
            .step-description {
                display: none !important;
            }
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-grid {
            display: block;
        }

        .form-grid .form-group {
            margin-bottom: 20px;
        }

        .form-buttons {
            margin-top: 24px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .form-grid .form-group {
                margin-bottom: 16px;
            }
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: var(--dark);
            font-weight: 500;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        label small {
            color: var(--gray);
            font-weight: 400;
            text-transform: none;
            letter-spacing: 0;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"] {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: var(--white);
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(91, 33, 182, 0.1);
        }

        input.error {
            border-color: var(--danger);
            background: #fef2f2;
        }

        input.success {
            border-color: var(--success);
            background: #f0fdf4;
        }

        /* Notifications */
        .notification {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            font-size: 14px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .notification-icon {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .notification.success {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            color: #065f46;
            border: 1px solid #86efac;
        }

        .notification.error {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .notification.warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            color: #92400e;
            border: 1px solid #fde047;
        }

        .notification.info {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        /* Buttons */
        .button-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
            gap: 12px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 100px;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(91, 33, 182, 0.3);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover:not(:disabled)::before {
            left: 100%;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(91, 33, 182, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: transparent;
            color: var(--gray);
            border: 1.5px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--light);
            border-color: var(--gray);
            color: var(--dark);
        }

        .btn-skip {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 100px;
            position: relative;
            overflow: hidden;
        }

        .btn-skip::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-skip:hover::before {
            left: 100%;
        }

        .btn-skip:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(245, 158, 11, 0.4);
        }

        .btn-icon {
            width: 14px;
            height: 14px;
        }

        /* Help text */
        .help-text {
            font-size: 12px;
            color: var(--gray);
            text-align: center;
            margin-top: 16px;
        }

        .help-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .help-text a:hover {
            text-decoration: underline;
        }

        /* Requirements Check */
        .requirement-item {
            padding: 10px 14px;
            margin-bottom: 8px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            font-size: 13px;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .requirement-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.8s;
        }

        .requirement-item:hover::before {
            left: 100%;
        }

        .requirement-item.success {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #86efac;
            color: #065f46;
        }

        .requirement-item.error {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .requirement-item:hover {
            transform: translateX(4px);
        }

        .requirement-icon {
            width: 16px;
            height: 16px;
            margin-right: 10px;
            flex-shrink: 0;
        }

        /* Loading spinner */
        .spinner {
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Completion section - matches other step designs */
        .completion-section {
            text-align: center;
            margin-bottom: 24px;
        }

        .completion-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Remove old success styles that were too different */
        .success-checkmark {
            display: none;
        }

        .success-title, .success-subtitle {
            display: none;
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .checkmark-icon {
            color: white;
            font-size: 32px;
            animation: drawCheck 0.4s ease-out 0.2s both;
        }

        @keyframes drawCheck {
            from {
                opacity: 0;
                transform: scale(0) rotate(-45deg);
            }
            to {
                opacity: 1;
                transform: scale(1) rotate(0);
            }
        }

        .success-title {
            color: var(--dark);
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
            text-align: center;
        }

        .success-subtitle {
            color: var(--gray);
            font-size: 14px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Center the success checkmark */
        #finish-content .success-checkmark {
            margin: 0 auto 20px;
        }
		
        /* Modal Overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 20px;
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Modal Container */
        .modal-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                0 0 100px rgba(91, 33, 182, 0.2);
            max-width: 450px;
            width: 100%;
            transform: scale(0.8) translateY(20px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .modal-overlay.show .modal-container {
            transform: scale(1) translateY(0);
        }

        /* Modal Header */
        .modal-header {
            position: relative;
            padding: 24px 24px 0;
            text-align: center;
            overflow: hidden;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                #f59e0b 0%, 
                #ef4444 25%, 
                #f59e0b 50%, 
                #ef4444 75%, 
                #f59e0b 100%);
            background-size: 200% auto;
            animation: gradientMove 3s linear infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        /* Modal Icon */
        .modal-icon-container {
            position: relative;
            margin: 0 auto 20px;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-icon-outer {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(239, 68, 68, 0.1) 100%);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.2); opacity: 0.1; }
        }

        .modal-icon-inner {
            position: relative;
            z-index: 2;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
            animation: iconBounce 0.5s ease-out;
        }

        @keyframes iconBounce {
            0% { transform: scale(0) rotate(-180deg); }
            50% { transform: scale(1.1) rotate(-90deg); }
            100% { transform: scale(1) rotate(0deg); }
        }

        .modal-icon {
            color: white;
            font-size: 28px;
            animation: iconShake 0.5s ease-out 0.3s;
        }

        @keyframes iconShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-3px); }
            75% { transform: translateX(3px); }
        }

        /* Modal Content */
        .modal-content {
            padding: 0 24px 24px;
            text-align: center;
			width: 100%;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .modal-message {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 28px;
            white-space: pre-line;
        }

        /* Modal Actions */
        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .modal-btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 120px;
            position: relative;
            overflow: hidden;
        }

        .modal-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .modal-btn:hover:not(:disabled)::before {
            left: 100%;
        }

        .modal-btn-primary {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);
        }

        .modal-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        }

        .modal-btn-secondary {
            background: #f8fafc;
            color: #64748b;
            border: 2px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .modal-btn-secondary:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #475569;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .modal-btn-icon {
            width: 16px;
            height: 16px;
        }

        /* Close Button */
        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 32px;
            height: 32px;
            background: rgba(107, 114, 128, 0.1);
            border: none;
            border-radius: 8px;
            color: #6b7280;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            font-size: 18px;
			z-index: 1;
        }

        .modal-close:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            transform: scale(1.1);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .modal-container {
                margin: 20px;
                max-width: none;
            }
            
            .modal-actions {
                flex-direction: column;
            }
            
            .modal-btn {
                width: 100%;
            }
            
            .modal-content {
                padding: 0 20px 20px;
            }
            
            .modal-header {
                padding: 20px 20px 0;
            }
        }

        /* Animation classes for different modal types */
        .modal-container.success .modal-icon-inner {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
        }

        .modal-container.error .modal-icon-inner {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 20px rgba(239, 68, 68, 0.4);
        }

        .modal-container.info .modal-icon-inner {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
        }

        /* Loading state */
        .modal-btn.loading {
            opacity: 0.7;
            cursor: not-allowed;
            pointer-events: none;
        }

        .btn-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Backdrop click prevention during loading */
        .modal-overlay.no-close {
            pointer-events: auto;
        }

        .modal-overlay.no-close .modal-container {
            pointer-events: auto;
        }

        /* Footer */
        .footer-text {
            text-align: center;
            margin-top: 24px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-text p {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 8px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            font-size: 12px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--primary-light);
        }

        .copyright {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .card-body {
                padding: 20px;
            }
            
            .step-label {
                font-size: 10px;
            }

            .step-circle {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }

            .button-group {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
            }

            .footer-links {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="background-animation">
        <div class="orb orb1"></div>
        <div class="orb orb2"></div>
        <div class="orb orb3"></div>
        <div class="orb orb4"></div>
        <div class="grid-overlay"></div>
        <div class="particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
    </div>

    <div class="container">
        <div class="installation-card">
            <div class="card-header">
                <img src="../assets/images/logo-dark.svg" alt="Keydera" class="logo">
                <h1>Installation Wizard</h1>
            </div>

            <div class="card-body">
                <!-- Progress Steps - Redesigned -->
                <div class="progress-container">
                    <div class="progress-percentage" id="progress-percent">0% Complete</div>
                    <div class="progress-line-container">
                        <div class="progress-line-bg"></div>
                        <div class="progress-line-fill" id="progress-fill"></div>
                    </div>
                    <div class="progress-steps">
                        <div class="step active" id="step-1" onclick="navigateToStep(1)">
                            <div class="step-wrapper">
                                <div class="step-circle-outer"></div>
                                <div class="step-circle">
                                    <span class="step-number">1</span>
                                    <div class="step-icon">!</div>
                                </div>
                            </div>
                            <div class="step-info">
                                <div class="step-label">Requirements</div>
                                <div class="step-description">System Check</div>
                            </div>
                        </div>
                        <div class="step" id="step-2" onclick="navigateToStep(2)">
                            <div class="step-wrapper">
                                <div class="step-circle-outer"></div>
                                <div class="step-circle">
                                    <span class="step-number">2</span>
                                    <div class="step-icon">!</div>
                                </div>
                            </div>
                            <div class="step-info">
                                <div class="step-label">License</div>
                                <div class="step-description">Activation</div>
                            </div>
                        </div>
                        <div class="step" id="step-3" onclick="navigateToStep(3)">
                            <div class="step-wrapper">
                                <div class="step-circle-outer"></div>
                                <div class="step-circle">
                                    <span class="step-number">3</span>
                                    <div class="step-icon">!</div>
                                </div>
                            </div>
                            <div class="step-info">
                                <div class="step-label">Database</div>
                                <div class="step-description">Configuration</div>
                            </div>
                        </div>
                        <div class="step" id="step-4" onclick="navigateToStep(4)">
                            <div class="step-wrapper">
                                <div class="step-circle-outer"></div>
                                <div class="step-circle">
                                    <span class="step-number">4</span>
                                    <div class="step-icon">!</div>
                                </div>
                            </div>
                            <div class="step-info">
                                <div class="step-label">Admin Account</div>
                                <div class="step-description">Create Admin</div>
                            </div>
                        </div>
                        <div class="step" id="step-5" onclick="navigateToStep(5)">
                            <div class="step-wrapper">
                                <div class="step-circle-outer"></div>
                                <div class="step-circle">
                                    <span class="step-number">5</span>
                                    <div class="step-icon">🎉</div>
                                </div>
                            </div>
                            <div class="step-info">
                                <div class="step-label">Complete</div>
                                <div class="step-description">All Done!</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Requirements Check -->
                <div id="requirements-content" class="step-content">
                    <div class="requirement-item success">
                        <svg class="requirement-icon" fill="#10b981" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        PHP Version 7.4.0 or higher
                    </div>
                    <div class="requirement-item success">
                        <svg class="requirement-icon" fill="#10b981" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        PDO PHP Extension
                    </div>
                    <div class="requirement-item success">
                        <svg class="requirement-icon" fill="#10b981" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        OpenSSL PHP Extension
                    </div>
                    <div class="requirement-item success">
                        <svg class="requirement-icon" fill="#10b981" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        cURL PHP Extension
                    </div>
                    <div class="requirement-item success">
                        <svg class="requirement-icon" fill="#10b981" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        ZIP PHP Extension
                    </div>

                    <div class="button-group">
                        <div class="help-text" style="margin: 0; flex: 1; text-align: left;">
                            By proceeding, you agree to the <a href="#">Terms & Privacy</a>
                        </div>
                        <button class="btn btn-primary" onclick="goToStep(2)">
                            Continue
                            <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Step 2: License Verification -->
                <div id="verify-content" class="step-content" style="display:none;">
                    <form onsubmit="verifyLicense(event); return false;">
                        <div class="form-group">
                            <label for="license">Purchase Code</label>
                            <input type="text" id="license" name="license" placeholder="Enter your purchase code" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Envato Username</label>
                            <input type="text" id="username" name="username" placeholder="Enter your envato username" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address <small>(for password recovery)</small></label>
                            <input type="email" id="email" name="email" placeholder="your@email.com" required>
                        </div>

                        <div class="button-group">
                            <button type="button" class="btn btn-secondary" onclick="goToStep(1)">
                                <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Back
                            </button>
                            <button type="button" class="btn btn-skip" onclick="skipActivation()">
                                Activate Later
                                <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5.59L7.3 8.64a.75.75 0 10-1.1 1.02l3.25 3.5a.75.75 0 001.1 0l3.25-3.5a.75.75 0 10-1.1-1.02l-1.95 2.1V5z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <button type="submit" class="btn btn-primary" id="verify-btn">
                                Verify
                                <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                    <p class="help-text" style="margin-top: 12px;">
                        <svg style="width: 12px; height: 12px; display: inline-block; margin-right: 4px; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        You can skip activation now and activate your license later from the admin panel.
                    </p>
                </div>

                <!-- Step 3: Database Configuration -->
                <div id="database-content" class="step-content" style="display:none;">
                    <form action="index.php?step=1" method="POST" id="database-form">
                        <!-- Hidden fields from previous step -->
                        <input type="hidden" name="user_email" id="hidden_email" value="">
                        <input type="hidden" name="prc3" id="hidden_prc3" value="">
                        <input type="hidden" name="sql_data" id="hidden_sql_data" value="">
                        
                        <div class="form-group">
                            <label for="db-host">Database Host</label>
                            <input type="text" id="db-host" name="host" placeholder="localhost" value="localhost" required>
                        </div>
                        <div class="form-group">
                            <label for="db-port">Database Port</label>
                            <input type="number" id="db-port" name="port" placeholder="3306" value="3306" required>
                        </div>
                        <div class="form-group">
                            <label for="db-user">Database Username</label>
                            <input type="text" id="db-user" name="user" placeholder="root" required>
                        </div>
                        <div class="form-group">
                            <label for="db-pass">Database Password</label>
                            <input type="password" id="db-pass" name="pass" placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label for="db-name">Database Name</label>
                            <input type="text" id="db-name" name="name" placeholder="keydera_db" required>
                        </div>

                        <div id="db-test-result"></div>

                        <div class="button-group">
                            <button type="button" class="btn btn-secondary" onclick="goToStep(2)">
                                <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Back
                            </button>
                            <button type="button" class="btn btn-primary" id="test-db-btn" onclick="testDatabase()">
                                Test Connection
                                <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <button type="submit" class="btn btn-primary" id="db-btn" style="display:none;">
                                Create Database
                                <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                    <p class="help-text">First test your database connection, then proceed with installation.</p>
                </div>

                <!-- Step 4: Admin Account Creation -->
                <div id="admin-content" class="step-content" style="display:none;">
                    <h2>Create Admin Account</h2>
                    <p>Create your administrator account to manage Keydera.</p>
                    
                    <form action="index.php?step=2" method="POST" id="admin-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="admin-username">Username *</label>
                                <input type="text" id="admin-username" name="admin_username" placeholder="Enter admin username" required>
                            </div>
                            <div class="form-group">
                                <label for="admin-email">Email Address *</label>
                                <input type="email" id="admin-email" name="admin_email" placeholder="admin@example.com" required>
                            </div>
                            <div class="form-group">
                                <label for="admin-password">Password *</label>
                                <input type="password" id="admin-password" name="admin_password" placeholder="Enter a secure password" required minlength="8">
                            </div>
                            <div class="form-group">
                                <label for="admin-password-confirm">Confirm Password *</label>
                                <input type="password" id="admin-password-confirm" name="admin_password_confirm" placeholder="Confirm your password" required minlength="8">
                            </div>
                        </div>
                        
                        <div class="notification info">
                            <svg class="notification-icon" fill="#1e40af" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div style="text-align: left;">
                                <strong>Password Requirements:</strong><br>
                                • Minimum 8 characters<br>
                                • Use a strong, unique password<br>
                                • This will be your main admin account
                            </div>
                        </div>
                        
                        <div class="form-buttons">
                            <button type="submit" class="btn btn-primary" id="admin-btn">
                                Create Admin Account
                                <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                    <p class="help-text">This account will have full administrative privileges.</p>
                </div>

                <!-- Step 5: Installation Complete -->
                <div id="finish-content" class="step-content" style="display:none;">
                    <div class="completion-section">
                        <div class="completion-icon">
                            <svg width="48" height="48" fill="#10b981" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 style="color: var(--dark); font-size: 20px; font-weight: 600; margin: 16px 0 8px 0; text-align: center;">Installation Complete!</h2>
                        <p style="color: var(--gray); font-size: 14px; margin-bottom: 24px; text-align: center;">Keydera has been successfully installed with your admin account.</p>
                    </div>
                    
                    <div class="notification success">
                        <svg class="notification-icon" fill="#059669" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div style="text-align: left;">
                            <strong>Next Steps:</strong><br>
                            • Use your created admin credentials to login<br>
                            • Configure your license settings<br>
                            • Set up your first product
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn btn-secondary" onclick="goToStep(4)">
                            <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Back
                        </button>
                        <button type="button" class="btn btn-primary" onclick="completedInstallation()">
                            Go to Dashboard
                            <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>

                    <p class="help-text">
                        Installation files will remain for troubleshooting. 
                        <a href="index.php?reinstall=1" style="color: var(--primary);">Reinstall</a> if needed.
                    </p>
                </div>
            </div>
        </div>

        <div class="footer-text">
            <p>KEYDERA INSTALLATION WIZARD v1.0.1</p>
            <div class="footer-links">
                <a href="https://docs.keydera.com" target="_blank">Documentation</a>
                <a href="mailto:support@craadly.com">Support</a>
                <a href="https://keydera.com/changelog" target="_blank">Changelog</a>
            </div>
            <div class="copyright">
                © 2024 Keydera. All rights reserved. Powered by Craadly Technologies.
            </div>
        </div>
    </div>

    <!-- Enhanced Confirmation Modal -->
    <div class="modal-overlay" id="confirmationModal">
        <div class="modal-container" id="modalContainer">
            <button class="modal-close" onclick="hideConfirmationModal()" aria-label="Close">×</button>
            
            <div class="modal-header">
                <div class="modal-icon-container">
                    <div class="modal-icon-outer"></div>
                    <div class="modal-icon-inner">
                        <div class="modal-icon" id="modalIcon">⚠️</div>
                    </div>
                </div>
            </div>
            
            <div class="modal-content">
                <h3 class="modal-title" id="modalTitle">Confirm Action</h3>
                <p class="modal-message" id="modalMessage">Are you sure you want to proceed?</p>
                
                <div class="modal-actions">
                    <button class="modal-btn modal-btn-secondary" id="cancelBtn" onclick="hideConfirmationModal()">
                        <svg class="modal-btn-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Cancel
                    </button>
                    <button class="modal-btn modal-btn-primary" id="confirmBtn" onclick="confirmAction()">
                        <svg class="modal-btn-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        let completedSteps = [];
        let confirmationCallback = null;
        let currentModalResolve = null;

        // Enhanced Confirmation Modal Functions
        function showConfirmationModal(options = {}) {
            return new Promise((resolve) => {
                currentModalResolve = resolve;
                
                const modal = document.getElementById('confirmationModal');
                const container = document.getElementById('modalContainer');
                const title = document.getElementById('modalTitle');
                const message = document.getElementById('modalMessage');
                const confirmBtn = document.getElementById('confirmBtn');
                const cancelBtn = document.getElementById('cancelBtn');
                const icon = document.getElementById('modalIcon');
                
                // Set default options
                const config = {
                    type: 'warning',
                    title: 'Confirm Action',
                    message: 'Are you sure you want to proceed?',
                    confirmText: 'Confirm',
                    cancelText: 'Cancel',
                    confirmIcon: '✓',
                    cancelIcon: '×',
                    allowClose: true,
                    showCancel: true,
                    ...options
                };
                
                // Update content
                title.textContent = config.title;
                message.textContent = config.message;
                confirmBtn.innerHTML = `
                    <svg class="modal-btn-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    ${config.confirmText}
                `;
                
                // Handle cancel button visibility
                if (config.showCancel) {
                    cancelBtn.style.display = 'inline-flex';
                    cancelBtn.innerHTML = `
                        <svg class="modal-btn-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        ${config.cancelText}
                    `;
                } else {
                    cancelBtn.style.display = 'none';
                }
                
                // Update modal type and icon
                container.className = `modal-container ${config.type}`;
                
                switch(config.type) {
                    case 'success':
                        icon.textContent = '✓';
                        break;
                    case 'error':
                        icon.textContent = '×';
                        break;
                    case 'info':
                        icon.textContent = 'i';
                        break;
                    case 'warning':
                    default:
                        icon.textContent = '⚠️';
                        break;
                }
                
                // Handle close button visibility
                const closeBtn = modal.querySelector('.modal-close');
                closeBtn.style.display = config.allowClose ? 'flex' : 'none';
                
                // Show modal
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
                
                // Focus management
                setTimeout(() => {
                    confirmBtn.focus();
                }, 300);
            });
        }

        function hideConfirmationModal() {
            const modal = document.getElementById('confirmationModal');
            modal.classList.remove('show');
            document.body.style.overflow = '';
            
            if (currentModalResolve) {
                currentModalResolve(false);
                currentModalResolve = null;
            }
        }

        function confirmAction() {
            const confirmBtn = document.getElementById('confirmBtn');
            const originalContent = confirmBtn.innerHTML;
            
            // Add loading state
            confirmBtn.classList.add('loading');
            confirmBtn.innerHTML = `
                <div class="btn-spinner"></div>
                Processing...
            `;
            
            // Prevent closing during loading
            const modal = document.getElementById('confirmationModal');
            modal.classList.add('no-close');
            
            // Simulate processing delay
            setTimeout(() => {
                if (currentModalResolve) {
                    currentModalResolve(true);
                    currentModalResolve = null;
                }
                
                // Reset states
                confirmBtn.classList.remove('loading');
                confirmBtn.innerHTML = originalContent;
                modal.classList.remove('no-close');
                
                hideConfirmationModal();
            }, 800);
        }

        function updateProgressBar() {
            const progressFill = document.getElementById('progress-fill');
            const progressPercent = document.getElementById('progress-percent');
            const percentage = ((currentStep - 1) / 4) * 100; // 5 steps total: 0, 25, 50, 75, 100%
            
            progressFill.style.width = percentage + '%';
            
            // Update percentage text
            progressPercent.textContent = Math.round(percentage) + '% Complete';
            progressPercent.classList.add('show');
            
            // Hide percentage after 2 seconds
            setTimeout(() => {
                progressPercent.classList.remove('show');
            }, 2000);
        }

        function navigateToStep(stepNumber) {
            // Only allow navigation to completed steps or current step
            if (stepNumber > currentStep && !completedSteps.includes(stepNumber - 1)) {
                // Show notification that previous steps must be completed
                showConfirmationModal({
                    type: 'info',
                    title: 'Complete Previous Steps',
                    message: 'Please complete the previous steps before proceeding to this step.',
                    confirmText: 'Got it',
                    showCancel: false,
                    allowClose: true
                });
                return;
            }
            
            if (stepNumber <= currentStep || completedSteps.includes(stepNumber)) {
                goToStep(stepNumber);
            }
        }

        function goToStep(stepNumber) {
            // Hide all content with fade out
            document.querySelectorAll('.step-content').forEach(content => {
                content.style.display = 'none';
            });

            // Remove active class from all steps
            document.querySelectorAll('.step').forEach(step => {
                step.classList.remove('active');
            });

            // Mark previous steps as completed
            for (let i = 1; i < stepNumber; i++) {
                const step = document.getElementById(`step-${i}`);
                step.classList.add('completed');
                step.classList.remove('active');
                
                // Add step to completed array if not already there
                if (!completedSteps.includes(i)) {
                    completedSteps.push(i);
                }
                
                // Update step icon
                const stepIcon = step.querySelector('.step-icon');
                if (stepIcon) {
                    stepIcon.innerHTML = '✓';
                }
            }

            // Show current step content with fade in
            let contentId = '';
            switch(stepNumber) {
                case 1:
                    contentId = 'requirements-content';
                    break;
                case 2:
                    contentId = 'verify-content';
                    break;
                case 3:
                    contentId = 'database-content';
                    break;
                case 4:
                    contentId = 'admin-content';
                    break;
                case 5:
                    contentId = 'finish-content';
                    // Mark step 5 as completed when reached
                    const step5El = document.getElementById('step-5');
                    if (step5El) {
                        step5El.classList.add('completed');
                        const step5Icon = step5El.querySelector('.step-icon');
                        if (step5Icon) {
                            step5Icon.innerHTML = '🎉';
                            step5Icon.style.display = 'flex';
                        }
                    }
                    
                    // Update progress to 100%
                    const progressFill = document.getElementById('progress-fill');
                    const progressPercent = document.getElementById('progress-percent');
                    if (progressFill && progressPercent) {
                        progressFill.style.width = '100%';
                        progressPercent.textContent = '100% Complete';
                        progressPercent.classList.add('show');
                    }
                    break;
            }
            
            if (contentId) {
                const content = document.getElementById(contentId);
                if (content) {
                    content.style.display = 'block';
                    content.style.animation = 'fadeIn 0.5s ease-out';
                }
            }

            // Mark current step as active (except for step 5 which should be completed)
            if (stepNumber < 5) {
                const currentStepEl = document.getElementById(`step-${stepNumber}`);
                currentStepEl.classList.add('active');
                
                // Update step icon for active step
                const stepIcon = currentStepEl.querySelector('.step-icon');
                if (stepIcon) {
                    stepIcon.innerHTML = '→';
                }
            }

            currentStep = stepNumber;
            updateProgressBar();
            
            // Scroll to top smoothly
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function skipActivation() {
            // Get email value (still required for password recovery)
            const email = document.getElementById('email').value;
            
            if (!email) {
                // Show error if email is not provided
                const existingNotif = document.querySelector('#verify-content .notification');
                if (existingNotif) {
                    existingNotif.remove();
                }
                
                const notification = document.createElement('div');
                notification.className = 'notification warning';
                notification.innerHTML = `
                    <svg class="notification-icon" fill="#f59e0b" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Please enter your email address for password recovery before skipping.
                `;
                
                const form = document.querySelector('#verify-content form');
                form.parentNode.insertBefore(notification, form);
                
                // Focus on email field
                document.getElementById('email').focus();
                return;
            }
            
            // Show beautiful confirmation modal instead of basic confirm()
            showConfirmationModal({
                type: 'warning',
                title: 'Skip License Activation?',
                message: 'Are you sure you want to skip license activation?\n\nYou can activate your license later from the admin panel, but some features may be limited until activation.',
                confirmText: 'Skip Activation',
                cancelText: 'Go Back',
                allowClose: true
            }).then((confirmed) => {
                if (confirmed) {
                    // Set dummy values for skipped activation
                    document.getElementById('hidden_email').value = email;
                    document.getElementById('hidden_prc3').value = 'skipped';
                    document.getElementById('hidden_sql_data').value = 'trial';
                    
                    // Show info notification
                    const existingNotif = document.querySelector('#verify-content .notification');
                    if (existingNotif) {
                        existingNotif.remove();
                    }
                    
                    const notification = document.createElement('div');
                    notification.className = 'notification info';
                    notification.innerHTML = `
                        <svg class="notification-icon" fill="#1e40af" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Skipping activation. You can activate later from the admin panel.
                    `;
                    
                    const form = document.querySelector('#verify-content form');
                    form.parentNode.insertBefore(notification, form);
                    
                    // Move to database step after a short delay
                    setTimeout(() => {
                        goToStep(3);
                    }, 1500);
                }
                // If cancelled, just stay on current step
            });
        }

        function verifyLicense(event) {
            event.preventDefault();
            
            const btn = document.getElementById('verify-btn');
            const originalContent = btn.innerHTML;
            
            // Get form data
            const license = document.getElementById('license').value;
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            
            // Show loading state
            btn.innerHTML = 'Verifying <span class="spinner"></span>';
            btn.disabled = true;
            
            // Create form data for AJAX request
            const formData = new FormData();
            formData.append('license', license);
            formData.append('client', username);
            formData.append('email', email);
            
            // Make AJAX request to verify license
            fetch('index.php?step=0', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                // Parse the response to check if verification was successful
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const successNotif = doc.querySelector('.notification.is-success');
                const errorNotif = doc.querySelector('.notification.is-danger');
                
                if (successNotif) {
                    // Extract hidden values from the response
                    const hiddenEmail = doc.querySelector('input[name="user_email"]');
                    const hiddenPrc3 = doc.querySelector('input[name="prc3"]');
                    const hiddenSqlData = doc.querySelector('input[name="sql_data"]');
                    
                    if (hiddenEmail && hiddenPrc3 && hiddenSqlData) {
                        // Store values for next step
                        document.getElementById('hidden_email').value = hiddenEmail.value;
                        document.getElementById('hidden_prc3').value = hiddenPrc3.value;
                        document.getElementById('hidden_sql_data').value = hiddenSqlData.value;
                    }
                    
                    // Show success notification
                    const existingNotif = document.querySelector('#verify-content .notification');
                    if (existingNotif) {
                        existingNotif.remove();
                    }
                    
                    const notification = document.createElement('div');
                    notification.className = 'notification success';
                    notification.innerHTML = `
                        <svg class="notification-icon" fill="#10b981" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        License verified successfully!
                    `;
                    
                    const form = document.querySelector('#verify-content form');
                    form.parentNode.insertBefore(notification, form);
                    
                    // Reset button
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                    
                    // Move to next step after delay
                    setTimeout(() => {
                        goToStep(3);
                    }, 1000);
                } else {
                    // Show error with modal
                    const errorMsg = errorNotif ? errorNotif.textContent.trim() : 'Verification failed. Please check your license.';
                    
                    showConfirmationModal({
                        type: 'error',
                        title: 'License Verification Failed',
                        message: `${errorMsg}\n\nWould you like to try again or skip activation?`,
                        confirmText: 'Try Again',
                        cancelText: 'Skip Activation'
                    }).then((tryAgain) => {
                        if (tryAgain) {
                            // Clear form and try again
                            document.getElementById('license').value = '';
                            document.getElementById('license').focus();
                        } else {
                            skipActivation();
                        }
                    });
                    
                    // Reset button
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Reset button
                btn.innerHTML = originalContent;
                btn.disabled = false;
                
                // Show error modal
                showConfirmationModal({
                    type: 'error',
                    title: 'Server Error',
                    message: 'Unable to connect to the server. Please check your internet connection and try again.',
                    confirmText: 'Try Again',
                    cancelText: 'Skip',
                    allowClose: true
                }).then((tryAgain) => {
                    if (tryAgain) {
                        verifyLicense(event);
                    } else {
                        skipActivation();
                    }
                });
            });
        }

        function testDatabase() {
            const btn = document.getElementById('test-db-btn');
            const originalContent = btn.innerHTML;
            
            // Get form values
            const host = document.getElementById('db-host').value;
            const port = document.getElementById('db-port').value;
            const user = document.getElementById('db-user').value;
            const pass = document.getElementById('db-pass').value;
            const name = document.getElementById('db-name').value;
            
            // Validate inputs
            if (!host || !port || !user || !name) {
                showDbResult('error', 'Please fill in all required fields.');
                return;
            }
            
            // Show loading state
            btn.innerHTML = 'Testing <span class="spinner"></span>';
            btn.disabled = true;
            
            // Create a test connection request
            const formData = new FormData();
            formData.append('test_connection', 'true');
            formData.append('host', host);
            formData.append('port', port);
            formData.append('user', user);
            formData.append('pass', pass);
            formData.append('name', name);
            
            // Make AJAX request to test database connection
            fetch('test_db.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showDbResult('success', data.message || 'Database connection successful! You can now create the database.');
                    
                    // Show the create database button
                    document.getElementById('db-btn').style.display = 'inline-flex';
                    document.getElementById('test-db-btn').style.display = 'none';
                } else if (data.status === 'warning') {
                    // Show warning modal for existing database
                    showConfirmationModal({
                        type: 'warning',
                        title: 'Database Already Exists',
                        message: 'The database contains existing data. This action will permanently delete all current data.\n\nAre you sure you want to continue?',
                        confirmText: 'Overwrite Database',
                        cancelText: 'Cancel'
                    }).then((confirmed) => {
                        if (confirmed) {
                            showDbResult('warning', data.message || 'Database exists with data. Proceeding will overwrite existing data.');
                            
                            // Show create button but with warning
                            document.getElementById('db-btn').style.display = 'inline-flex';
                            document.getElementById('test-db-btn').style.display = 'none';
                            
                            // Change button text to indicate overwrite
                            document.getElementById('db-btn').innerHTML = `
                                Overwrite Database
                                <svg class="btn-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            `;
                        } else {
                            // Keep test button visible
                            document.getElementById('db-btn').style.display = 'none';
                            document.getElementById('test-db-btn').style.display = 'inline-flex';
                        }
                    });
                } else {
                    showDbResult('error', data.message || 'Database connection failed. Please check your credentials.');
                    
                    // Keep test button visible
                    document.getElementById('db-btn').style.display = 'none';
                    document.getElementById('test-db-btn').style.display = 'inline-flex';
                }
                
                // Reset test button
                btn.innerHTML = originalContent;
                btn.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                showDbResult('error', 'Connection test failed. Please check your database settings.');
                
                // Reset button
                btn.innerHTML = originalContent;
                btn.disabled = false;
                
                // Keep test button visible
                document.getElementById('db-btn').style.display = 'none';
                document.getElementById('test-db-btn').style.display = 'inline-flex';
            });
        }

        function showDbResult(type, message) {
            const resultDiv = document.getElementById('db-test-result');
            let iconColor = '#10b981'; // success green
            let iconPath = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>';
            
            if (type === 'error') {
                iconColor = '#ef4444'; // error red
                iconPath = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>';
            } else if (type === 'warning') {
                iconColor = '#f59e0b'; // warning orange
                iconPath = '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>';
            }
            
            resultDiv.innerHTML = `
                <div class="notification ${type}">
                    <svg class="notification-icon" fill="${iconColor}" viewBox="0 0 20 20">
                        ${iconPath}
                    </svg>
                    ${message}
                </div>
            `;
        }

        // Initialize the database form to work with PHP
        document.addEventListener('DOMContentLoaded', function() {
            const dbForm = document.getElementById('database-form');
            if (dbForm) {
                dbForm.addEventListener('submit', function(e) {
                    const btn = document.getElementById('db-btn');
                    btn.innerHTML = 'Creating Database <span class="spinner"></span>';
                    btn.disabled = true;
                    // Form will submit normally to PHP
                });
            }

            // Initialize admin form validation
            const adminForm = document.getElementById('admin-form');
            if (adminForm) {
                adminForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const password = document.getElementById('admin-password').value;
                    const confirmPassword = document.getElementById('admin-password-confirm').value;
                    
                    if (password !== confirmPassword) {
                        showConfirmationModal({
                            type: 'error',
                            title: 'Password Mismatch',
                            message: 'The passwords do not match. Please make sure both password fields contain the same value.',
                            confirmText: 'OK',
                            showCancel: false
                        });
                        return;
                    }
                    
                    if (password.length < 8) {
                        showConfirmationModal({
                            type: 'error',
                            title: 'Password Too Short',
                            message: 'The password must be at least 8 characters long. Please choose a stronger password.',
                            confirmText: 'OK',
                            showCancel: false
                        });
                        return;
                    }
                    
                    // If validation passes, submit the form
                    const btn = document.getElementById('admin-btn');
                    btn.innerHTML = 'Creating Admin Account <span class="spinner"></span>';
                    btn.disabled = true;
                    
                    // Submit form to PHP
                    adminForm.submit();
                });
            }
        });

        // Add input validation feedback
        document.querySelectorAll('input[required]').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('error');
                    this.classList.remove('success');
                } else {
                    this.classList.add('success');
                    this.classList.remove('error');
                }
            });
            
            input.addEventListener('input', function() {
                this.classList.remove('error', 'success');
            });
        });

        // Initialize progress bar
        updateProgressBar();

        // Handle URL hash navigation
        function handleHashNavigation() {
            const hash = window.location.hash;
            if (hash) {
                const stepMatch = hash.match(/#step-(\d+)/);
                if (stepMatch) {
                    const stepNum = parseInt(stepMatch[1]);
                    if (stepNum >= 1 && stepNum <= 5) {
                        goToStep(stepNum);
                    }
                }
            }
        }

        // Handle browser back/forward buttons
        window.addEventListener('popstate', handleHashNavigation);
        
        // Handle initial page load
        document.addEventListener('DOMContentLoaded', function() {
            handleHashNavigation();
            
            // Check for error messages in URL
            const urlParams = new URLSearchParams(window.location.search);
            const errorMessage = urlParams.get('error');
            if (errorMessage) {
                showConfirmationModal({
                    type: 'error',
                    title: 'Installation Error',
                    message: decodeURIComponent(errorMessage),
                    confirmText: 'OK',
                    showCancel: false,
                    allowClose: true
                });
                
                // Clean the URL
                const cleanUrl = window.location.pathname + window.location.hash;
                window.history.replaceState({}, document.title, cleanUrl);
            }
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.ctrlKey) {
                const activeContent = document.querySelector('.step-content:not([style*="display: none"])');
                const primaryBtn = activeContent?.querySelector('.btn-primary');
                if (primaryBtn && !primaryBtn.disabled) {
                    primaryBtn.click();
                }
            }
            
            // Close modal on escape key
            if (e.key === 'Escape') {
                const modal = document.getElementById('confirmationModal');
                if (modal.classList.contains('show') && !modal.classList.contains('no-close')) {
                    hideConfirmationModal();
                }
            }
        });

        // Close modal on backdrop click
        document.getElementById('confirmationModal').addEventListener('click', function(e) {
            if (e.target === this && !this.classList.contains('no-close')) {
                hideConfirmationModal();
            }
        });

        // Prevent modal content clicks from closing modal
        document.getElementById('modalContainer').addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Add dynamic particle generation
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 20 + 's';
            particle.style.animationDuration = (15 + Math.random() * 10) + 's';
            document.querySelector('.particles').appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 25000);
        }

        // Create particles periodically
        setInterval(createParticle, 2000);

        // Enhanced notification system
        function showStepNotification(message, type = 'warning') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : 
                            type === 'error' ? 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)' :
                            'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)'};
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px ${type === 'success' ? 'rgba(16, 185, 129, 0.4)' : 
                                        type === 'error' ? 'rgba(239, 68, 68, 0.4)' :
                                        'rgba(245, 158, 11, 0.4)'};
                z-index: 1000;
                animation: slideIn 0.3s ease-out;
                font-size: 14px;
                max-width: 300px;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Enhanced error handling for various scenarios
        function handleDatabaseError(errorMessage) {
            showConfirmationModal({
                type: 'error',
                title: 'Database Connection Failed',
                message: `${errorMessage}\n\nWould you like to try again or modify your settings?`,
                confirmText: 'Try Again',
                cancelText: 'Modify Settings'
            }).then((confirmed) => {
                if (confirmed) {
                    testDatabase();
                } else {
                    // Focus on first input field
                    document.getElementById('db-host').focus();
                }
            });
        }

        // Installation complete handler
        function completeInstallation() {
            showConfirmationModal({
                type: 'success',
                title: 'Installation Complete!',
                message: 'Keydera has been successfully installed and configured.\n\nWould you like to go to the dashboard now?',
                confirmText: 'Go to Dashboard',
                cancelText: 'Stay Here'
            }).then((confirmed) => {
                if (confirmed) {
                    window.location.href = '../';
                }
            });
        }

        // Add custom confirmation for database overwrite
        function confirmDatabaseOverwrite() {
            return showConfirmationModal({
                type: 'error',
                title: 'Destructive Action Warning',
                message: 'This will permanently delete all existing data in the database.\n\nThis action cannot be undone. Are you absolutely sure?',
                confirmText: 'Yes, Delete Everything',
                cancelText: 'Cancel',
                allowClose: false
            });
        }

        // Enhanced form validation
        function validateForm(formId) {
            const form = document.getElementById(formId);
            const inputs = form.querySelectorAll('input[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('error');
                    isValid = false;
                } else {
                    input.classList.remove('error');
                    input.classList.add('success');
                }
            });
            
            if (!isValid) {
                showStepNotification('Please fill in all required fields.', 'error');
            }
            
            return isValid;
        }

        // Auto-save form data to prevent data loss
        function autoSaveFormData() {
            const forms = ['license', 'username', 'email', 'db-host', 'db-port', 'db-user', 'db-name'];
            
            forms.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    // Load saved data
                    const savedValue = localStorage.getItem(`keydera_install_${fieldId}`);
                    if (savedValue && !field.value) {
                        field.value = savedValue;
                    }
                    
                    // Save on input
                    field.addEventListener('input', function() {
                        localStorage.setItem(`keydera_install_${fieldId}`, this.value);
                    });
                }
            });
        }

        // Clear saved form data after successful installation
        function clearSavedFormData() {
            const forms = ['license', 'username', 'email', 'db-host', 'db-port', 'db-user', 'db-name'];
            forms.forEach(fieldId => {
                localStorage.removeItem(`keydera_install_${fieldId}`);
            });
        }

        // Initialize auto-save
        document.addEventListener('DOMContentLoaded', function() {
            autoSaveFormData();
        });

        // Enhanced step transition effects
        function enhanceStepTransitions() {
            const steps = document.querySelectorAll('.step');
            steps.forEach((step, index) => {
                step.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('active') && !this.classList.contains('completed')) {
                        this.style.transform = 'translateY(-2px)';
                    }
                });
                
                step.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active') && !this.classList.contains('completed')) {
                        this.style.transform = 'translateY(0)';
                    }
                });
            });
        }

        // Initialize enhanced transitions
        enhanceStepTransitions();

        // Add progress sound effects (optional)
        function playProgressSound(type = 'success') {
            // Only play if user has interacted with the page (browser policy)
            if (typeof AudioContext !== 'undefined') {
                try {
                    const audioContext = new AudioContext();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    
                    oscillator.frequency.value = type === 'success' ? 800 : type === 'error' ? 300 : 600;
                    oscillator.type = 'sine';
                    
                    gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
                    
                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + 0.3);
                } catch (e) {
                    // Silently fail if audio is not supported
                }
            }
        }

        // Add visual feedback for successful actions
        function showSuccessAnimation(element) {
            element.style.animation = 'none';
            element.offsetHeight; // Trigger reflow
            element.style.animation = 'successPulse 0.6s ease-out';
            
            setTimeout(() => {
                element.style.animation = '';
            }, 600);
        }

        // Add success pulse animation
        const successStyle = document.createElement('style');
        successStyle.textContent = `
            @keyframes successPulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); box-shadow: 0 0 20px rgba(16, 185, 129, 0.4); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(successStyle);

        // Initialize all systems
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Keydera Installation Wizard v1.0.1 - Enhanced UI Loaded');
            
            // Add any additional initialization here
            updateProgressBar();
            enhanceStepTransitions();
            
            // Show welcome message
            setTimeout(() => {
                showStepNotification('Welcome to Keydera Installation Wizard!', 'success');
            }, 1000);
        });

        // Handle installation completion
        function completedInstallation() {
            showConfirmationModal({
                type: 'success',
                title: 'Installation Complete!',
                message: 'Keydera has been successfully installed. You will now be redirected to the dashboard where you can login with your admin credentials.',
                confirmText: 'Go to Dashboard',
                showCancel: false,
                allowClose: false
            }).then((confirmed) => {
                if (confirmed) {
                    // Show loading state
                    const btn = document.querySelector('.btn-primary[onclick="completedInstallation()"]');
                    if (btn) {
                        const originalContent = btn.innerHTML;
                        btn.innerHTML = 'Finalizing... <div class="btn-spinner"></div>';
                        btn.disabled = true;
                    }
                    
                    // Call step 5 completion endpoint to finalize installation
                    fetch('index.php?step=5', {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Redirect to the specified URL
                            setTimeout(() => {
                                window.location.href = data.redirect || '../';
                            }, 1000);
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Installation finalization error:', error);
                        // Still redirect as fallback
                        setTimeout(() => {
                            window.location.href = '../';
                        }, 1000);
                    });
                }
            });
        }

        // Export functions for external use if needed
        window.KeyderaInstaller = {
            showConfirmationModal,
            hideConfirmationModal,
            goToStep,
            skipActivation,
            testDatabase,
            showStepNotification,
            completeInstallation: completedInstallation
        };
    </script>
</body>
</html>