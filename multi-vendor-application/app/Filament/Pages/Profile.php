<?php

namespace App\Filament\Pages;

use App\Repositories\ProfileRepository;
use App\Traits\TokenManagementTrait;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use function config;
use function dd;

/**
 * Class Profile
 *
 * This class represents the page where users can view and update their profile information.
 * It extends the Filament Page component and integrates with the ProfileRepository to handle profile updates.
 *
 * @package App\Filament\Pages
 */
class Profile extends Page
{
    use TokenManagementTrait;
    /**
     * @var string|null The icon used in the navigation for the page.
     */
    protected static ?string $navigationIcon = 'heroicon-o-user';

    /**
     * @var string|null The label used in the navigation for the page.
     */
    protected static ?string $navigationLabel = 'My Profile';

    /**
     * @var string The view template for the page.
     */
    protected static string $view = 'filament.pages.profile';

    /**
     * @var ProfileRepository The repository used to handle profile updates.
     */
    protected $profileRepository;

    /**
     * @var mixed The currently authenticated user.
     */
    public $user;

    /**
     * @var array The data used for the profile form.
     */
    public array $formData = [];

    public $apiUrl;

    /**
     * Boot method to initialize the profile repository.
     *
     * @param ProfileRepository $profileRepository The profile repository to be injected.
     *
     * @return void
     */
    public function boot (ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
        $this->apiUrl = config ('app.api_url');
    }

    /**
     * Mount method to initialize the profile data for the page.
     * It loads the current authenticated user and their profile details.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->user = Auth::user()->load('profile');

        $this->formData = [
            'first_name'    => $this->user->profile?->first_name,
            'last_name'     => $this->user->profile?->last_name,
            'email'         => $this->user->email,
            'phone_number'  => $this->user->profile?->phone_number,
        ];
    }

    /**
     * Defines the form schema for the profile update form.
     *
     * @param Form $form The form instance.
     *
     * @return Form The updated form with the schema for the profile.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('formData');
    }

    /**
     * Returns the schema for the profile form fields.
     * It defines the form fields for the first name, last name, phone number, and email.
     *
     * @return array The form schema.
     */
    protected function getFormSchema(): array
    {
        return [
            Section::make('Customer Profile')
                   ->schema([
                       TextInput::make('first_name')
                                ->label('First Name')
                                ->maxLength(255)
                                ->required(),
                       TextInput::make('last_name')
                                ->label('Last Name')
                                ->maxLength(255)
                                ->required(),
                       TextInput::make('phone_number')
                                ->label('Phone Number')
                                ->maxLength(255)
                                ->rule(Rule::unique('profiles', 'phone_number')->ignore(Auth::user()->profile->id)),
                       TextInput::make('email')
                                ->label('Email Address')
                                ->disabled()
                                ->dehydrated(false),
                   ])->columns(2),

            Actions::make([
                Action::make('update')
                      ->label('Save Changes')
                      ->icon('heroicon-o-pencil')
                      ->action('updateProfile'),

            ])->columnSpanFull(),
        ];
    }

    /**
     * Handles the profile update process.
     * It updates the user's profile data in the database and sends a notification.
     *
     * @return void
     */
    public function updateProfile(): void
    {
        $data = $this->form->getState();

        if ($this->user->profile) {
            $this->profileRepository->update($this->user->profile->id, [
                'first_name'   => $data['first_name'],
                'last_name'    => $data['last_name'],
                'phone_number' => $data['phone_number'],
            ]);
        }

        Notification::make()
                    ->title('Profile Updated')
                    ->success()
                    ->send();
    }
}
