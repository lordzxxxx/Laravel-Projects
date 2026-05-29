<?php

namespace App\Models;

use App\Models\Concerns\UsesTenantConnectionForTenantData;
use App\Support\MessageAttachments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;
    use UsesTenantConnectionForTenantData;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'booking_id',
        'tenant_id',
        'subject',
        'content',
        'attachment_path',
        'type',
        'status',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // Type constants
    const TYPE_GENERAL = 'general';

    const TYPE_BOOKING_INQUIRY = 'booking_inquiry';

    const TYPE_BOOKING_RESPONSE = 'booking_response';

    const TYPE_COMPLAINT = 'complaint';

    const TYPE_FEEDBACK = 'feedback';

    // Status constants
    const STATUS_SENT = 'sent';

    const STATUS_READ = 'read';

    const STATUS_ARCHIVED = 'archived';

    // Relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('status', self::STATUS_SENT);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('receiver_id', $userId);
    }

    public function scopeFromUser($query, $userId)
    {
        return $query->where('sender_id', $userId);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_SENT => 'Unread',
            self::STATUS_READ => 'Read',
            self::STATUS_ARCHIVED => 'Archived',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            self::TYPE_GENERAL => 'General',
            self::TYPE_BOOKING_INQUIRY => 'Booking Inquiry',
            self::TYPE_BOOKING_RESPONSE => 'Booking Response',
            self::TYPE_COMPLAINT => 'Complaint',
            self::TYPE_FEEDBACK => 'Feedback',
        ];

        return $labels[$this->type] ?? $this->type;
    }

    public function getIsUnreadAttribute()
    {
        return $this->status === self::STATUS_SENT;
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return MessageAttachments::url($this->attachment_path);
    }

    public function getExcerptAttribute(): string
    {
        return MessageAttachments::excerpt($this->content, $this->attachment_path);
    }

    public function hasAttachment(): bool
    {
        return filled($this->attachment_path);
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'status' => self::STATUS_READ,
            'read_at' => now(),
        ]);
    }

    public function archive()
    {
        $this->update(['status' => self::STATUS_ARCHIVED]);
    }

    public function reply($content, $sender, ?string $attachmentPath = null)
    {
        $receiverId = (int) $sender->id === (int) $this->sender_id
            ? $this->receiver_id
            : $this->sender_id;

        return self::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'booking_id' => $this->booking_id,
            'tenant_id' => $this->tenant_id,
            'subject' => 'Re: '.($this->subject ?? ''),
            'content' => (string) $content,
            'attachment_path' => $attachmentPath,
            'type' => $this->type === self::TYPE_BOOKING_INQUIRY ? self::TYPE_BOOKING_RESPONSE : self::TYPE_GENERAL,
        ]);
    }
}
