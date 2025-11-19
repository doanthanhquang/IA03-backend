<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * Get list of mailboxes/folders
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMailboxes(Request $request)
    {
        $mailboxes = [
            [
                'id' => 'inbox',
                'name' => 'Inbox',
                'icon' => 'inbox',
                'unreadCount' => 12,
                'totalCount' => 156,
            ],
            [
                'id' => 'starred',
                'name' => 'Starred',
                'icon' => 'star',
                'unreadCount' => 3,
                'totalCount' => 24,
            ],
            [
                'id' => 'sent',
                'name' => 'Sent',
                'icon' => 'send',
                'unreadCount' => 0,
                'totalCount' => 89,
            ],
            [
                'id' => 'drafts',
                'name' => 'Drafts',
                'icon' => 'file-text',
                'unreadCount' => 0,
                'totalCount' => 7,
            ],
            [
                'id' => 'archive',
                'name' => 'Archive',
                'icon' => 'archive',
                'unreadCount' => 0,
                'totalCount' => 432,
            ],
            [
                'id' => 'trash',
                'name' => 'Trash',
                'icon' => 'trash',
                'unreadCount' => 0,
                'totalCount' => 23,
            ],
            [
                'id' => 'work',
                'name' => 'Work',
                'icon' => 'briefcase',
                'unreadCount' => 5,
                'totalCount' => 68,
            ],
            [
                'id' => 'personal',
                'name' => 'Personal',
                'icon' => 'user',
                'unreadCount' => 2,
                'totalCount' => 45,
            ],
        ];

        return response()->json([
            'success' => true,
            'mailboxes' => $mailboxes,
        ]);
    }

    /**
     * Get emails for a specific mailbox
     *
     * @param  string  $mailboxId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmails(Request $request, $mailboxId)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 50);

        // Mock email data
        $allEmails = $this->getMockEmails($mailboxId);

        $total = count($allEmails);
        $start = ($page - 1) * $perPage;
        $emails = array_slice($allEmails, $start, $perPage);

        return response()->json([
            'success' => true,
            'emails' => $emails,
            'pagination' => [
                'page' => $page,
                'perPage' => $perPage,
                'total' => $total,
                'totalPages' => ceil($total / $perPage),
            ],
        ]);
    }

    /**
     * Get single email detail
     *
     * @param  string  $emailId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmailDetail(Request $request, $emailId)
    {
        $email = [
            'id' => $emailId,
            'from' => [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@techcorp.com',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Sarah',
            ],
            'to' => [
                [
                    'name' => 'You',
                    'email' => 'me@example.com',
                ],
            ],
            'cc' => [
                [
                    'name' => 'John Doe',
                    'email' => 'john.doe@techcorp.com',
                ],
            ],
            'subject' => 'Q4 Project Update - Important',
            'body' => '<div style="font-family: Arial, sans-serif; line-height: 1.6;">
                <p>Hi there,</p>
                <p>I wanted to provide you with an update on the Q4 project status. We\'ve made significant progress over the past few weeks:</p>
                <ul>
                    <li><strong>Phase 1:</strong> Successfully completed user research and requirements gathering</li>
                    <li><strong>Phase 2:</strong> Design mockups approved by stakeholders</li>
                    <li><strong>Phase 3:</strong> Development is 60% complete</li>
                </ul>
                <p>We\'re on track to meet the December 15th deadline. However, we need to schedule a review meeting to discuss some architectural decisions.</p>
                <p><strong>Action Items:</strong></p>
                <ol>
                    <li>Review the attached technical specification document</li>
                    <li>Provide feedback by end of this week</li>
                    <li>Schedule a follow-up meeting for next Monday</li>
                </ol>
                <p>Please let me know if you have any questions or concerns.</p>
                <p>Best regards,<br>Sarah Johnson<br>Senior Project Manager<br>TechCorp Inc.</p>
            </div>',
            'timestamp' => now()->subHours(2)->toIso8601String(),
            'read' => true,
            'starred' => true,
            'hasAttachments' => true,
            'attachments' => [
                [
                    'id' => 'att1',
                    'name' => 'Q4_Technical_Spec.pdf',
                    'size' => '2.4 MB',
                    'type' => 'application/pdf',
                    'url' => '#',
                ],
                [
                    'id' => 'att2',
                    'name' => 'Project_Timeline.xlsx',
                    'size' => '1.1 MB',
                    'type' => 'application/vnd.ms-excel',
                    'url' => '#',
                ],
            ],
            'labels' => ['important', 'work'],
        ];

        return response()->json([
            'success' => true,
            'email' => $email,
        ]);
    }

    /**
     * Generate mock emails based on mailbox
     *
     * @param  string  $mailboxId
     * @return array
     */
    private function getMockEmails($mailboxId)
    {
        $baseEmails = [
            [
                'id' => 'email1',
                'from' => [
                    'name' => 'Sarah Johnson',
                    'email' => 'sarah.johnson@techcorp.com',
                    'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Sarah',
                ],
                'subject' => 'Q4 Project Update - Important',
                'preview' => 'Hi there, I wanted to provide you with an update on the Q4 project status. We\'ve made significant progress...',
                'timestamp' => now()->subHours(2)->toIso8601String(),
                'read' => false,
                'starred' => true,
                'hasAttachments' => true,
                'labels' => ['important', 'work'],
            ],
            [
                'id' => 'email2',
                'from' => [
                    'name' => 'GitHub',
                    'email' => 'notifications@github.com',
                    'avatar' => 'https://api.dicebear.com/7.x/identicon/svg?seed=GitHub',
                ],
                'subject' => '[myrepo] Pull Request #234: Add authentication feature',
                'preview' => 'John Doe wants to merge 5 commits into main from feature/auth. Review the changes and provide feedback...',
                'timestamp' => now()->subHours(5)->toIso8601String(),
                'read' => false,
                'starred' => false,
                'hasAttachments' => false,
                'labels' => ['github', 'review'],
            ],
            [
                'id' => 'email3',
                'from' => [
                    'name' => 'Jennifer Lee',
                    'email' => 'jennifer.lee@company.com',
                    'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Jennifer',
                ],
                'subject' => 'Meeting Notes - Team Sync 11/15',
                'preview' => 'Thanks everyone for joining today\'s sync. Here are the key takeaways and action items from our discussion...',
                'timestamp' => now()->subHours(8)->toIso8601String(),
                'read' => true,
                'starred' => false,
                'hasAttachments' => true,
                'labels' => ['meetings'],
            ],
            [
                'id' => 'email4',
                'from' => [
                    'name' => 'Michael Chen',
                    'email' => 'mchen@designstudio.com',
                    'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Michael',
                ],
                'subject' => 'Re: Design Feedback Request',
                'preview' => 'I\'ve reviewed the latest mockups and overall they look great! Just a few minor suggestions on the color scheme...',
                'timestamp' => now()->subDay()->toIso8601String(),
                'read' => true,
                'starred' => true,
                'hasAttachments' => false,
                'labels' => ['design'],
            ],
            [
                'id' => 'email5',
                'from' => [
                    'name' => 'LinkedIn',
                    'email' => 'notifications@linkedin.com',
                    'avatar' => 'https://api.dicebear.com/7.x/identicon/svg?seed=LinkedIn',
                ],
                'subject' => 'Your weekly network update',
                'preview' => 'See who\'s viewed your profile this week and discover new opportunities to connect with professionals...',
                'timestamp' => now()->subDays(2)->toIso8601String(),
                'read' => false,
                'starred' => false,
                'hasAttachments' => false,
                'labels' => ['social'],
            ],
            [
                'id' => 'email6',
                'from' => [
                    'name' => 'Alex Martinez',
                    'email' => 'alex.martinez@startup.io',
                    'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Alex',
                ],
                'subject' => 'Coffee chat next week?',
                'preview' => 'Hey! It\'s been a while since we last caught up. Would you be free for a coffee chat sometime next week?',
                'timestamp' => now()->subDays(2)->toIso8601String(),
                'read' => true,
                'starred' => false,
                'hasAttachments' => false,
                'labels' => ['personal'],
            ],
            [
                'id' => 'email7',
                'from' => [
                    'name' => 'Emma Wilson',
                    'email' => 'emma.wilson@finance.com',
                    'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Emma',
                ],
                'subject' => 'Invoice #INV-2024-1156',
                'preview' => 'Please find attached the invoice for services rendered in November 2024. Payment is due within 30 days...',
                'timestamp' => now()->subDays(3)->toIso8601String(),
                'read' => false,
                'starred' => false,
                'hasAttachments' => true,
                'labels' => ['finance', 'important'],
            ],
            [
                'id' => 'email8',
                'from' => [
                    'name' => 'David Park',
                    'email' => 'dpark@consulting.com',
                    'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=David',
                ],
                'subject' => 'Proposal for Q1 2025 Strategy',
                'preview' => 'I\'ve drafted a comprehensive proposal for our Q1 2025 strategy. Please review and let me know your thoughts...',
                'timestamp' => now()->subDays(4)->toIso8601String(),
                'read' => true,
                'starred' => true,
                'hasAttachments' => true,
                'labels' => ['strategy', 'review'],
            ],
            [
                'id' => 'email9',
                'from' => [
                    'name' => 'Newsletter',
                    'email' => 'weekly@techdigest.com',
                    'avatar' => 'https://api.dicebear.com/7.x/identicon/svg?seed=Newsletter',
                ],
                'subject' => 'This Week in Tech: AI Breakthroughs and More',
                'preview' => 'Your weekly roundup of the most important tech news, including major AI developments, startup funding...',
                'timestamp' => now()->subDays(5)->toIso8601String(),
                'read' => true,
                'starred' => false,
                'hasAttachments' => false,
                'labels' => ['newsletter'],
            ],
            [
                'id' => 'email10',
                'from' => [
                    'name' => 'Rachel Green',
                    'email' => 'rachel.green@marketing.com',
                    'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Rachel',
                ],
                'subject' => 'Marketing Campaign Results',
                'preview' => 'Great news! Our latest campaign exceeded expectations with a 45% increase in engagement. Here\'s the full report...',
                'timestamp' => now()->subDays(6)->toIso8601String(),
                'read' => false,
                'starred' => false,
                'hasAttachments' => true,
                'labels' => ['marketing', 'results'],
            ],
            [
                'id' => 'email11',
                'from' => [
                    'name' => 'Support Team',
                    'email' => 'support@service.com',
                    'avatar' => 'https://api.dicebear.com/7.x/identicon/svg?seed=Support',
                ],
                'subject' => 'Re: Ticket #45678 - Issue Resolved',
                'preview' => 'We\'re happy to inform you that your support ticket has been resolved. Please let us know if you need further assistance...',
                'timestamp' => now()->subWeek()->toIso8601String(),
                'read' => true,
                'starred' => false,
                'hasAttachments' => false,
                'labels' => ['support'],
            ],
            [
                'id' => 'email12',
                'from' => [
                    'name' => 'Tom Anderson',
                    'email' => 'tanderson@agency.com',
                    'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Tom',
                ],
                'subject' => 'Partnership Opportunity',
                'preview' => 'I came across your profile and think there could be a great partnership opportunity between our companies...',
                'timestamp' => now()->subWeek()->toIso8601String(),
                'read' => false,
                'starred' => false,
                'hasAttachments' => false,
                'labels' => ['business'],
            ],
        ];

        // Filter or modify based on mailbox
        switch ($mailboxId) {
            case 'starred':
                return array_filter($baseEmails, fn ($e) => $e['starred']);
            case 'sent':
                // Modify to show as sent emails
                $sentEmails = array_map(function ($e) {
                    $e['from'] = ['name' => 'Me', 'email' => 'me@example.com', 'avatar' => null];

                    return $e;
                }, array_slice($baseEmails, 0, 6));

                return $sentEmails;
            case 'drafts':
                return array_slice($baseEmails, 0, 3);
            case 'trash':
                return array_slice($baseEmails, -4);
            case 'work':
                return array_filter($baseEmails, fn ($e) => in_array('work', $e['labels'] ?? []));
            case 'personal':
                return array_filter($baseEmails, fn ($e) => in_array('personal', $e['labels'] ?? []));
            default:
                return $baseEmails;
        }
    }
}
